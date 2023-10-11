<?php

namespace App\Console\Commands;

use HttpRequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class moveS3CameraTag extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'video:moveS3 
		{--start=1 : Hvilken side vi starter med at hente assets }
		{--max=200 : Max antal sider vi henter assets }
	';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Tjek alle video id fra CameraTag og sammenlign med hvad vi har i database. Hvis vi ikke har videoen, slettes den. Env variables: CT_KEY and CAMERATAG_API required.';

	const GET_ASSETS_URL = "https://www.cameratag.com/api/v15/apps/%s/assets.json"; // GET request!
	const UPDATE_VIDEO_URL = "https://www.cameratag.com/api/v15/assets/%s.json"; // PUT request!

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		try {
			$cameraTagUUID = $this->fetchCameraTagVideos();
			//$cameraTagUUID = ['v-b163332a-8f1c-48ed-b560-9b53358183b5'];
		} catch (HttpRequestException $e) {
			$this->error("Error fetching CameraTag videos! Error: $e");
		}

		foreach ($cameraTagUUID as $uuid) {
			$this->info($uuid);
		}
		//$this->updateCameraTagVideos($cameraTagUUID);
	}

	private function debug(string $besked): void
	{
		if ($this->option('verbose')) {
			$this->comment($besked);
		}
	}

	/**
	 * Fetching all the videos from cameratag, and returns a collection with UUIDs.
	 *
	 * @return Collection|mixed
	 * @throws HttpRequestException
	 */
	private function fetchCameraTagVideos()
	{
		$startPage = intval($this->option('start'));
		$maxPages = intval($this->option('max')); // If no is set, using magic number 200. Around 197 pages in CameraTag.

		$this->info("Henter information!\nFra side: $startPage\nMax sider: $maxPages");

		$page = $startPage;
		$cameraTagUUID = [];

		// Start process:
		$bar = $this->output->createProgressBar($maxPages);
		$bar->start();

		while ($page < $startPage + $maxPages) {
			$result = $this->getAssets($page);
			$result = json_decode($result);

			if (count($result->assets) == 0) {
				break;
			}

			foreach ($result->assets as $video) {
				if (isset($video->medias) && !$this->s3_exist($video->medias)) {
					dd($video);
					$cameraTagUUID[] = $video->uuid;
				}
			}
			$page++;
			$bar->advance();
		}

		$bar->finish();
		$this->output->success("Hentet " . count($cameraTagUUID) . " video UUIDs fra " . $page - $startPage . " sider.");

		return $cameraTagUUID;
	}

	private function updateCameraTagVideos(array $toUpdate)
	{
		$bar = $this->output->createProgressBar(count($toUpdate));
		$bar->start();

		$updated = 0;

		// Loop
		foreach ($toUpdate as $video) {
			try {
				$this->updateAsset($video);
				$updated++;
			} catch (HttpRequestException $e) {
				$this->error("Error updating video! Error: $e");
			}
			$bar->advance();
		}

		$bar->finish();

		$this->output->success("Så er $updated videoer opdateret på CameraTag!");
	}

	/**
	 * @return bool|string
	 * @throws HttpRequestException
	 */
	private function getAssets(int $page)
	{
		$endpoint = sprintf(self::GET_ASSETS_URL, env('CT_KEY'));
		$params = array(
			'api_key' => env('CAMERATAG_API'),
			'type' => 'Video',
			'page' => $page
		);

		$url = $endpoint . '?' . http_build_query($params);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			throw new HttpRequestException("cURL Error #:" . $err);
		}

		return $response;
	}

	/**
	 * @return bool|string
	 * @throws HttpRequestException
	 */
	private function updateAsset(string $uuid)
	{
		$endpoint = sprintf(self::UPDATE_VIDEO_URL, $uuid);
		$params = array(
			'api_key' => env('CAMERATAG_API'),
			'state' => 'approved',
			'description' => 'Asset updated to sync the video into S3 bucket'
		);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => "PUT",
			CURLOPT_POSTFIELDS => $params
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			throw new HttpRequestException("cURL Error #:" . $err);
		}

		return $response;
	}

	private function s3_exist($medias)
	{
		foreach ($medias as $media) {
			if ($media->media_type == "mp4" && isset($media->urls->s3)) {
				return true;
			}
		}
		return false;
	}

	/** Use later, to scan URL to videos! */
	private function search_s3($medias)
	{
		$urls = collect();

		foreach ($medias as $media) {
			if ($media->media_type == "mp4" && isset($media->urls->s3)) {
				$urls->put(($media->width * $media->height), $media->urls->s3);
			}
		}

		if ($urls->isNotEmpty()) {
			$urls->sortKeys();
			return $urls->first();
		} else {
			return null;
		}
	}
}
