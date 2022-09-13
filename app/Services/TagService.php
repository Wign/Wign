<?php

namespace App\Services;

use App\Sign;
use App\Tag;
use URL;

define( 'REGEXP', config( 'wign.tagRegexp' ) );

class TagService {

	public function getAllTags() {
		return Tag::all();
	}

	public static function replaceTagsToURL( string $text ): string {
		$replaceWith = '<a href="' . URL::to( config( "wign.urlPath.tags" ) ) . '/$1">$0</a>';
        return preg_replace( REGEXP, $replaceWith, $text );
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
			$tag = Tag::firstOrCreate( [ 'tag' => $hashtag ] );
			$sign->tags()->attach( $tag );
		}

		return true;
	}

	public function findTagByName( string $tag ): Tag {
		return Tag::where( 'tag', $tag )->first();
	}

	public function findTagByID( int $id ): Tag {
		return Tag::find( $id );
	}

	private static function findTagsInText( String $text ): array {
		$tagArray = [];
		preg_match_all( REGEXP, $text, $tagArray );

		return $tagArray['tags'];
	}

	public function getTaggedSigns( Tag $tag ) {
		return $tag->signs()->get();
	}

	public function getQueriedTags( string $search ) {
		return Tag::getQueriedTag($search)->get();
	}

}