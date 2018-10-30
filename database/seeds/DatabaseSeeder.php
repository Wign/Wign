<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
        //Model::unguard();

		$this->call( UsersTableSeeder::class );
        $this->call( PostTableSeeder::class );
		$this->call( RequestWordTableSeeder::class );
        $this->call( LikeTableSeeder::class );
        $this->call( AliasTableSeeder::class );
        $this->call( ReviewTableSeeder::class );
        $this->call( RemotionTableSeeder::class );

        echo "Model: visible / trashed / total seeded\n";
        echo "Users: " . \App\User::count() . " / " . \App\User::onlyTrashed()->count() . " / " .\App\User::withTrashed()->count() . "\n";
        echo "QCVs: "  . \App\Qcv::count() . " / " . \App\Qcv::onlyTrashed()->count() . " / " .\App\Qcv::withTrashed()->count() . "\n";
        echo "Posts: "  . \App\Post::count() . "\n";
        echo "ILs: "  . \App\Il::count() . " / " . \App\Il::onlyTrashed()->count() . " / " .\App\Il::withTrashed()->count() . "\n";
        echo "Videos: "  . \App\Video::count() . " / " . \App\Video::onlyTrashed()->count() . " / " .\App\Video::withTrashed()->count() . "\n";
        echo "Descriptions: "  . \App\Description::count() . " / " . \App\Description::onlyTrashed()->count() . " / " .\App\Description::withTrashed()->count() . "\n";
        echo "Tags: "  . \App\Tag::count() . " / " . \App\Tag::onlyTrashed()->count() . " / " .\App\Tag::withTrashed()->count() . "\n";
        echo "Words: "  . \App\Word::count() . " / " . \App\Word::onlyTrashed()->count() . " / " .\App\Word::withTrashed()->count() . "\n";
        echo "Reviews: "  . \App\Review::count() . " / " . \App\Review::onlyTrashed()->count() . " / " .\App\Review::withTrashed()->count() . "\n";
        echo "Remotions: "  . \App\Remotion::count() . " / " . \App\Remotion::onlyTrashed()->count() . " / " .\App\Remotion::withTrashed()->count() . "\n";

        //echo "Likes: "  . \App\Post::likes()->count() . "\n";
        //echo "Taggables: "  . \App\Description::tags()->count() . "\n";
        //echo "Wordlink"  . \App\Post::words()->count() .  "\n";
        //echo "Aliases: "  . \App\Word::alias_parents()->count() . "\n";
        //echo "Request words: "  . \App\Word::requests()->count() . "\n";
        //echo "Review votings: "  . \App\Review::voters()->count() . "\n";
        //echo "Remotion votings: "  . \App\Remotion::voters()->count() . "\n";


        //Model::reguard();
	}
}