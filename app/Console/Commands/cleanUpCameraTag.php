<?php

namespace App\Console\Commands;

use App\Sign;
use HttpRequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class cleanUpCameraTag extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'cleanup:video 
		{--start=1 : Hvilken side vi starter med at hente assets }
		{--max=250 : Max antal sider vi henter assets }
		{--dryrun : Vælges hvis det skal blive gjort "dry" - altså ikke reelt fjerne videoerne hos CameraTag.}
	';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Tjek alle video id fra CameraTag og sammenlign med hvad vi har i database. Hvis vi ikke har videoen, slettes den. Env variables: CT_KEY and CAMERATAG_API required.';

	const GET_ASSETS_URL = "https://www.cameratag.com/api/v15/apps/%s/assets.json"; // GET request!
	const DELETE_VIDEO_URL = "https://www.cameratag.com/api/v15/assets/%s.json"; // DELETE request!

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
		} catch (HttpRequestException $e) {
			$this->error("Error fetching CameraTag videos! Error: $e");
		}

		$signUuids = Sign::all()->pluck('video_uuid');
		$this->info("Sammelinger med " . $signUuids->count() . " videoer fra databasen");

		$toDelete = new Collection();

		foreach ($cameraTagUUID as $ct) {
			if ($signUuids->contains($ct['uuid'])) {
				$this->debug("Fundet! " . $ct['uuid'] . " - URL: " . $ct['url']);
			} else {
				$toDelete->add($ct);
			}
		}

		$this->removeCameraTagVideos($toDelete);
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
		$maxPages = intval($this->option('max')); // If no is set, using magic number 250. Around 232 pages in CameraTag.

		$this->info("Henter information!\nFra side: $startPage\nMax sider: $maxPages");

		$page = $startPage;
		$newContent = 1;
		$cameraTagUUID = collect();

		// Start process:
		$bar = $this->output->createProgressBar($maxPages);
		$bar->start();

		while ($page < $startPage + $maxPages && $newContent > 0) {
			$newContent = 0;
			$result = $this->getAssets($page);
			$result = json_decode($result);

			foreach ($result->assets as $video) {
				$url = isset($video->medias) ? $video->medias?->qvga_mp4?->urls?->cameratag : "ERROR!";
				$cameraTagUUID->push(['uuid' => $video->uuid, 'url' => $url]);
				$newContent++;
			}
			$page++;
			$bar->advance();
		}

		$bar->finish();
		$this->output->success("Hentet " . $cameraTagUUID->count() . " video UUIDs fra " . $page - $startPage . " sider.");

		return $cameraTagUUID;
	}

	private function removeCameraTagVideos(Collection $toDelete)
	{
		$dryrun = $this->option('dryrun');

		$this->info("Videoer vi skal fjerne fra CameraTag: " . $toDelete->count());

		if (!$dryrun) {
			$bar = $this->output->createProgressBar($toDelete->count());
			$bar->start();
		}

		$slettet = 0;

		// Loop
		foreach ($toDelete as $video) {
			if ($dryrun) {
				$this->line("UUID ikke fundet: " . $video['uuid'] . " - URL: " . $video['url'] . " - DRYRUN!");
			} else {
				try {
					$this->removeAsset($video['uuid']);
					$slettet++;
				} catch (HttpRequestException $e) {
					$this->error("Error deleting video! Error: $e");
				}
			}
			if (!$dryrun) {
				$bar->advance();
			}
		}

		if (!$dryrun) {
			$bar->finish();
		}
		$this->output->success("Så er $slettet videoer slettet fra CameraTag!");
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
	private function removeAsset(string $uuid)
	{
		$endpoint = sprintf(self::DELETE_VIDEO_URL, $uuid);
		$params = array(
			'api_key' => env('CAMERATAG_API')
		);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => "DELETE",
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
}
