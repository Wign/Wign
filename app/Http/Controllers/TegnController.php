<?php namespace App\Http\Controllers;

use App\Word;
use App\Sign;
use App\Helpers\ClientHelper;

use DB;
use URL;
use Input;
use Illuminate\Http\Request;
use Redirect;

class TegnController extends Controller {

    public function visTegn($word = null) {
        if ($word != null) { $word = mellemrum($word); }
        $wordData = Word::where('word', $word)->first();

        if($wordData['id']) {
            $hasSigns = Sign::where('word_id', $wordData['id'])->count();
        }
        if($wordData['id'] && $hasSigns) {

            $signs2 = DB::select(DB::raw('
                SELECT signs.*, COUNT(votes.id) AS sign_count, GROUP_CONCAT(votes.ip ORDER BY votes.id) AS votesIP
                FROM signs LEFT JOIN votes
                ON signs.id = votes.sign_id
                WHERE signs.word_id = :wordID AND signs.deleted_at IS NULL
                GROUP BY signs.id 
                ORDER BY sign_count DESC
            '), array('wordID' => $wordData["id"]));

	        //dd($signs2[0]);

            return view('sign')->with(array('word' => $wordData, 'signs' => $signs2));
        }
        else {
            if($word) {
                $words = Word::has('signs')->lists('word');

                for($i = 0; $i < count($words); $i++) {
                    $tempArr[$i] = levenshtein($word, $words[$i]); // @TODO : ÆNDRE DET!!
                }
                asort($tempArr);
                
                foreach ($tempArr as $key => $value) {
                    $sortedArr[] = $words[$key];
                }

                $suggestWords = array_slice($sortedArr, 0, 5, true);
            }
            else { $suggestWords = null; }
            return view('nosign')->with(['word' => $word, 'suggestions' => $suggestWords]);
        }
    }

    public function visSeneste()
    {
        $antal = 25;
        $words = Word::has('signs')->latest($antal)->get();
        
        return view('list')->with(['words' => $words, 'antal' => $antal]);
    }

    public function visAlle()
    {
        $words = Word::has('signs')->orderBy('word')->get();
        
        return view('listAll')->with(['words' => $words]);
    }

    public function gemTegn(Request $request)
    {
        $this->validate($request, [
            'tegn' => 'required|string',
            'beskr' => 'string',
            'wign01.video_uuid' => 'required' 
        ]);

        $q = $request->all();

        $hasWord = Word::firstOrCreate(['word' => $q['tegn']]);
        $wID = $hasWord->id;
        
        $signId = Sign::create(array(
            'word_id' => $wID,
            'description' => $q['beskr'],
            'video_uuid' => $q['wign01']['video_uuid'],
            'camera_uuid' => '',
            'video_url' => $q['wign01']['qvga']['video'],
            'video_url_mp4' => $q['wign01']['qvga']['mp4'],
            'video_url_webm' => $q['wign01']['qvga']['webm'],
            'thumbnail_url' => $q['wign01']['qvga']['thumb'],
            'small_thumbnail_url' => $q['wign01']['qvga']['small_thumb'],
            'ip' => $request->ip()
        ));

        if($signId) { 
            $tegn = $q['tegn'];
            $url = URL::to('/tegn/'.$tegn);
            $video = "//www.cameratag.com/videos/".$q['wign01']['video_uuid']."/qvga/mp4.mp4";
            $message = [
                "attachments" => [ [
                    "fallback" => "Videoen kan ses her: ".$video."!",
                    "color" => "good",
                    "pretext" => "Et ny tegn er kommet!",
                    "title" => $tegn,
                    "title_link" => $url,
                    "text" => "Se <".$video."|videoen>!",
                    "unfurl_links" => true,
                    "image_url" => "https:".$q['wign01']['qvga']['thumb'],
                    "thumb_url" => "https:".$q['wign01']['qvga']['small_thumb'],
                ]],
            ];
            ClientHelper::sendJSON($message, config('social.slack.webHook'));
            
            $flash = [
                'message' => 'Tegnet er oprettet. Tusind tak for din bidrag! Tryk her for at opret flere tegn',
                'url' => URL::to('/opret')
            ];
            return redirect('/tegn/'.$q['tegn'])->with($flash);
        }
    }

    public function flagSignView($id) { 

        $word = Sign::where('id', $id)->first()->word;
        $img = Sign::where('id', $id)->pluck('small_thumbnail_url');

        return view('form.flagSign')->with(['id' => $id, 'img' => $img, 'word' => $word]); 

    }

    public function flagSign(Request $request) {
        // Check if client is bot. If true, reject the flagging!
        $bot = ClientHelper::detect_bot();
        if($bot) {
            $flash = [
                'message' => 'Det ser ud til at du er en bot. Vi må desværre afvise din rapportering af tegnet!'
            ];
            return redirect('/')->with($flash);
        }

        $this->validate($request, [
            'content' => 'required',
            'email' => 'email'
        ]);

        $q = $request->all(); // content, commentar, id, email

        $theSign = Sign::where('id', $q['id'])->first();
        
        $theSign->flag_reason = $q['content'];
        $theSign->flag_comment = $q['commentar'];
        $theSign->flag_email = $q['email'];
        $theSign->flag_ip = $request->ip();

        $saved = $theSign->save();

        if($saved) {
            $deleted = $theSign->delete();
            
            if($deleted) {
                return Redirect::to('/')->with('message', 'Tusind tak for din rapportering af tegnet. Videoen er fjernet indtil vi kigger nærmere på den. Du hører fra os.');
            }
            else {
                $flash = [
                'message' => 'Der skete en fejl med at rapportere det. Prøv venligst igen, eller kontakt os i Wign. På forhånd tak.',
                'url' => 'mailto:'.config('wign.email')
                ];
                return Redirect::to('/flagSignView/'.$q['id'])->with($flash);
            }
        }
    }

}