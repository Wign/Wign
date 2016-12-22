<?php namespace App\Http\Controllers;

use App\Word;
use App\RequestWord;
use App\Helpers\ClientHelper;

use DB;
use URL;
use Request;
use Response;

class WordController extends Controller {

    public function createWord($word = null, $hasSign = null) {
        if($word) {
            $hasWord = Word::firstOrCreate(['word' => $word]);
            $hasSign = $hasWord->signs->first() ? 1 : 0;
        }
        return view('opret')->with(array('word' => $word, 'hasSign' => $hasSign));
    }

    public function listRequests() {
        $requests = DB::select(DB::raw('
            SELECT words.word, COUNT(request_words.id) AS request_count, GROUP_CONCAT(request_words.ip ORDER BY request_words.id) AS votesIP
            FROM words LEFT JOIN request_words
            ON words.id = request_words.word_id
            WHERE (SELECT count(*) FROM request_words WHERE request_words.word_id = words.id) >= 1 
                AND (SELECT count(*) FROM signs WHERE signs.word_id = words.id) <= 0
            GROUP BY words.id
            ORDER BY request_count DESC, words.word ASC
        '));
        //select * from `words` where (select count(*) from `request_words` where `request_words`.`word_id` = `words`.`id`) >= 1            

        return view('requests')->with('requests', $requests);
    }
    
    public function requestWord($word) {
        if ($word != null) { $word = mellemrum($word); }

        $myIP = Request::getClientIp();

        $hasWord = Word::firstOrCreate(['word' => $word]);

        $hasSign = $hasWord->signs->first();
        if($hasSign) {
            $flash = array(
                'message' => 'Vi har allerede tegnet for '.$word,
                'url' => URL::to('/tegn/'.$word)
            );
            return redirect('/requests')->with($flash);
        }
        
        $hasVote = $hasWord->request->where('ip', $myIP)->first();
        if($hasVote) {
            return redirect('/requests')->with('message', 'Du har allerede efterlyst '.$word.'!');
        }
            
        else {
            // Check if client is bot. If true, reject the creation!
            $bot = ClientHelper::detect_bot();
            if($bot) {
                $flash = [
                    'message' => 'Det ser ud til at du er en bot. Vi må desværre afvise din anmoding!'
                ];
                return redirect('/requests')->with($flash);
            }
            else {
                $requestID = RequestWord::create(['word_id' => $hasWord['id'], 'ip' => $myIP]);
                if($requestID) {
                    $flash = [
                        'message' => $word.' succesfuldt efterlyst! Nu skal du bare vente på at en anden opretter tegnet for '.$word.'.',
                        'url' => URL::to('/tegn/'.$word)
                    ];
                    return redirect('/requests')->with($flash);
                }
            }
        }
    }

    public function allWords_JSON($word = "") {
        if (isset($word)) { $word = mellemrum($word); }
        $words = Word::has('signs')->where('word', 'like', '%'.$word.'%')->get(array('word as label'));
        return $words;
    }

    public function likeWords($word) {
        // We use this method in TegnController->visTegn()
    }
}