<?php

namespace App\Console\Commands;

use App\Services\SignService;
use App\Services\TagService;
use Illuminate\Console\Command;
use App\Sign;

class makeTags extends Command {

	protected $signature = 'tags:make';
	protected $description = 'Read through all description of Signs to fetch hashtags to build the Tags database';

	/**
	 * The Tag service implementation
	 *
	 * @var TagService
	 */
	protected $tags = null;

	/**
	 * The Sign service implementation
	 *
	 * @var SignService
	 */
	protected $signs = null;

	/**
	 * Create a new command instance.
	 *
	 * @param TagService $tags
	 * @param SignService $signs
	 */
	public function __construct(TagService $tags, SignService $signs) {
		parent::__construct();
		$this->tags = $tags;
		$this->signs = $signs;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$this->line( 'Warning: It will take long time, and totally refreshes the Tags table in database.' );
		if ( $this->confirm( 'Do you wish to continue?' ) ) {

			$signs = $this->signs->getAllSigns();
			$bar   = $this->output->createProgressBar( count( $signs ) );

			foreach ( $signs as $sign ) {
				$this->tags->storeTags($sign);

				$bar->advance();
			}

			$bar->finish();

			// TODO: Make a clear on the tag_relation database and delete all tags with no usages.

			$this->output->success("All tags successfully inserted in database!");

		}
	}
}
