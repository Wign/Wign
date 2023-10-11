<?php

namespace App\Console\Commands;

use App\Sign;
use Carbon\Carbon;
use HttpRequestException;
use Illuminate\Console\Command;
use mysql_xdevapi\Exception;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use function PHPUnit\Framework\isEmpty;

class syncCameraTag extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'video:sync 
		{--id= : Sync en bestemt id (lokalt) }
		{--uuid= : Sync en bestemt uuid (video uuid fra CameraTag) }
	';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Synkronisere alt videoer fra vores database og hent de nyeste url samt play count ned, og opdatere navn på videoerne i CameraTag. Env variables: CT_KEY and CAMERATAG_API required.';

	const SHOW_ASSET_URL = "https://www.cameratag.com/api/v15/assets/%s.json"; // GET request!

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
		$id = $this->option('id');
		$uuid = $this->option('uuid');

		if (isset($id)) {
			$signs = Sign::whereId($id)->get();
		} elseif (isset($uuid)) {
			$signs = Sign::whereVideoUuid($uuid)->get();
		} else {
			$signs = Sign::whereDate('updated_at', '<', Carbon::now()->subWeeks(2))->get();
		}

		// Start process:
		$bar = $this->output->createProgressBar($signs->count());
		$bar->start();

		foreach ($signs as $sign) {
			try {
				$cameratag = $this->fetchCameraTagAsset($sign->video_uuid);
				$bar->advance();

				// Update content here!
				$sign->plays = $cameratag->getPlays();
				if($cameratag->getSmallThumbUrl() != null) {
					$sign->small_thumbnail_url = $cameratag->getSmallThumbUrl();
				}
				if($cameratag->getThumbUrl() != null) {
					$sign->thumbnail_url = $cameratag->getThumbUrl();
				}
				$sign->video_url = $cameratag->getVideoUrl();
				$sign->save();

			} catch (HttpRequestException $e) {
				$this->error("Error fetching CameraTag videos! Error: $e");
			}
		}

		$bar->finish();
	}

	/**
	 * Fetching all the videos from cameratag, and returns a DTO with data.
	 *
	 * @return cameraTagDTO
	 * @throws HttpRequestException
	 */
	private function fetchCameraTagAsset(string $uuid)
	{
		$result = $this->getAsset($uuid);
		$result = json_decode($result);

		$cameraTagDTO = new cameraTagDTO($uuid);

		$cameraTagDTO->setVideoUrl($this->search_s3_medias($result->medias, ["mp4"]));
		$cameraTagDTO->setThumbUrl($this->search_s3_medias($result->medias, ["thumb", "small_thumb"]));
		$cameraTagDTO->setSmallThumbUrl($this->search_s3_medias($result->medias, ["thumb", "small_thumb"], true));
		$cameraTagDTO->setPlays($result->play_count);

		return $cameraTagDTO;
	}

	/**
	 * @return bool|string
	 * @throws HttpRequestException
	 */
	private function getAsset(string $uuid)
	{
		$endpoint = sprintf(self::SHOW_ASSET_URL, $uuid);
		$params = array(
			'api_key' => env('CAMERATAG_API')
		);

		$url = $endpoint . '?' . http_build_query($params);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			throw new HttpRequestException("cURL Error #:" . $err);
		}

		return $response;
	}

	/** Scan medias for videos and other stuffs! */
	private function search_s3_medias($medias, array $mediaType, bool $descending = false)
	{
		$urls = collect();

		foreach ($medias as $media) {
			if (in_array($media->media_type, $mediaType) && $media->state == "COMPLETED" && isset($media->urls->s3)) {
				$urls->put(($media->width * $media->height), $media->urls->s3);
			}
		}

		if ($urls->isNotEmpty()) {
			$urls->sortKeys(SORT_REGULAR, $descending);
			return $urls->first();
		} else {
			return null;
		}
	}
}

class cameraTagDTO
{
	var string $uuid, $videoUrl;
	var ?string $thumbUrl, $smallThumbUrl;
	var int $plays;

	/**
	 * @param string $uuid
	 */
	public function __construct(string $uuid)
	{
		$this->uuid = $uuid;
	}

	/**
	 * @return string
	 */
	public function getUuid(): string
	{
		return $this->uuid;
	}

	/**
	 * @return string
	 */
	public function getVideoUrl(): string
	{
		return $this->videoUrl;
	}

	/**
	 * @param string $videoUrl
	 */
	public function setVideoUrl(string $videoUrl): void
	{
		$this->videoUrl = $videoUrl;
	}

	/**
	 * @return string
	 */
	public function getThumbUrl(): ?string
	{
		return $this->thumbUrl;
	}

	/**
	 * @param string $thumbUrl
	 */
	public function setThumbUrl(?string $thumbUrl): void
	{
		$this->thumbUrl = $thumbUrl;
	}

	/**
	 * @return string
	 */
	public function getSmallThumbUrl(): ?string
	{
		return $this->smallThumbUrl;
	}

	/**
	 * @param string $smallThumbUrl
	 */
	public function setSmallThumbUrl(?string $smallThumbUrl): void
	{
		$this->smallThumbUrl = $smallThumbUrl;
	}

	/**
	 * @return int
	 */
	public function getPlays(): int
	{
		return $this->plays;
	}

	/**
	 * @param int $plays
	 */
	public function setPlays(int $plays): void
	{
		$this->plays = $plays;
	}

}
