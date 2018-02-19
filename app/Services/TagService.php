<?php

namespace App\Services;

use App\Sign;
use App\Tag;
use Illuminate\Database\Eloquent\Model;

define( 'REGEXP', config( 'wign.tagRegexp' ) );

class TagService {

	public static function replaceTagsToURL( string $text ): string {
		$replaceWith = '<a href="' . \URL::to( config( "wign.urlPath.tags" ) ) . '/$1">$0</a>';
		$text = preg_replace( REGEXP, $replaceWith, $text );

		return $text;
	}

	public function storeTags( Sign $sign ): bool {
		$sign->tags()->detach(); // Delete all tags relations from the sign (Begin on fresh)

		$desc = $sign->description;

		if ( empty( $desc ) ) {
			return false;
		}

		$hashtags = $this::findTagsInText( $desc );

		if ( empty( $hashtags ) ) {
			return false;
		}

		foreach ( $hashtags as $hashtag ) {
			$tag = Tag::firstOrCreate( [ 'tags' => $hashtag ] );
			$sign->tags()->attach( $tag );
		}

		return true;
	}

	public function findTagByName( string $tag ) {
		if($tag == null) {
			return null;
		}

		return Tag::where('tags', $tag)->first();
	}

	private static function findTagsInText( String $text ): array {
		$tagArray = [];
		preg_match_all( REGEXP, $text, $tagArray );

		return $tagArray['tags'];
	}

	public static function getTaggedSigns( Model $tag ) {
		return $tag->signs()->get();
	}


}