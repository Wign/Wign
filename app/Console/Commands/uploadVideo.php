<?php

namespace App\Console\Commands;

use App\Sign;
use App\Word;
use HttpRequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SplFileInfo;

class uploadVideo extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'upload:video
	{stien : Den fulde sti til den mappe videoerne ligger. F.eks. "/Users/Dig/Documents/AalborgMatrCenter/". OBS! Det finder alle videoer recrusivt!}
	{description? : Beskrivelsen vi skal tilføje til alle videoerne}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Upload all video from a path to CameraTag and save in DB. Env variables: CT_KEY and CAMERATAG_API required.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$file_path = $this->argument('stien');
		$description = $this->argument('description');
		$files = File::allFiles($file_path);
		$numVideos = count($files);

		$this->info("Uploading videoes. Total videos: " . $numVideos);

		foreach ($files as $file) {
			try {
				$curlResponse = $this->getResponse( $file );
			} catch ( HttpRequestException $e ) {
				$this->warn( "The upload to Cameratag failed with code " . $e->getCode() . "! Error: " . $e->getMessage() );

				return;
			}

			$word = pathinfo( $file )['filename'];
			$word = $this->cleanWord( $word );

			$result = json_decode( $curlResponse );

			$existingWord = true;
			$findWord     = Word::where( [ 'word' => $word ] )->first();
			if ( $findWord == null ) {
				$findWord = Word::create( [ 'word' => $word ] );
				$existingWord = false;
			}
			$wordID = $findWord->id;

			$sign = Sign::create( array(
				'word_id'             => $wordID,
				'description'         => $description,
				'video_uuid'          => $result->uuid,
				'video_url'           => "//www.cameratag.com/assets/".$result->uuid."/vga_mp4.mp4",
				'thumbnail_url'       => "//www.cameratag.com/assets/".$result->uuid."/vga_thumb.jpg",
				'small_thumbnail_url' => "//www.cameratag.com/assets/".$result->uuid."/qvga_thumb.jpg",
				'ip'                  => $result->ip
			) );

			if ( $sign ) {
				$message = $word . ($existingWord ? " - EXISTING WORD" : "");
				$this->line($message);
			}
		}

		$this->info("\nAll videos successfully uploaded and persisted!");
	}

	/**
	 * @param $file
	 *
	 * @return SplFileInfo
	 * @throws HttpRequestException
	 */
	private function getResponse( SplFileInfo $file) {
		$endpoint  = "https://cameratag.com/v13/apps/".env('CT_KEY')."/videos";
		$api_key   = env('CAMERATAG_API');

		$curl  = curl_init();
		$cFile = curl_file_create( $file );
		$post  = array( 'api_key' => $api_key, 'video_file' => $cFile );

		curl_setopt_array( $curl, array(
			CURLOPT_URL            => $endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => $post,
			CURLOPT_HTTPHEADER     => array(
				"cache-control: no-cache",
				"content-type: multipart/form-data;"
			),
		) );

		$response = curl_exec( $curl );
		$err      = curl_error( $curl );

		curl_close( $curl );

		if ( $err ) {
			throw new HttpRequestException("cURL Error #:" . $err);
		}

		return $response;
	}

	private function cleanWord( $word ) {
		$word = str_replace("ë", "æ", $word);
		$word = str_replace("õ", "ø", $word);
		$word = str_replace("Ü", "å", $word);
		$word = str_replace("ù", "Ø", $word);
		$word = str_replace("è", "Å", $word);
		$word = str_replace("í", "Æ", $word);
		$word = str_replace("_", "", $word);
		$word = preg_replace('/\(\d+\)/', '', $word);
		$word = trim($word);
		return $word;
	}
}
