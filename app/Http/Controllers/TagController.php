<?php

namespace App\Http\Controllers;

use App\Description;
Use App\Tag;

class TagController extends Controller {

    /**
     * @param Description $desc
     * @return bool
     */
    public function storeTags( Description $desc ): bool {
        $desc->tags()->detach(); // Delete all tags relations from the post (Begin on fresh)

        $text = $desc->text;

        if ( empty( $text ) ) {
            return false;
        }

        $hashtags = self::findTagsInText( $text );

        if ( empty( $hashtags ) ) {
            return false;
        }

        foreach ( $hashtags as $hashtag ) {
            $tag = Tag::firstOrCreate( [ 'tag' => $hashtag ] );
            $desc->tags()->attach( $tag );
        }

        return true;
    }

	public function findTags( $tag ) {
		$theTag = self::findTagByName( $tag );

		$signs = self::getTaggedSigns( $theTag );
		if ( empty( $signs ) ) {
			abort( 404, __( 'text.sign.not.have' ) );
		}
		foreach ( $signs as $sign ) {
			$this->sign->isSignTagged( $sign );
			$this->sign->assignVotesToSign( $sign );
			$sign->theWord = $this->sign->getWordBySign( $sign );
		}

		// Sorts the posts according to the words (And not according to number of votes as in "post" page)
		$signs = $signs->sortBy( function ( $sign ) {
			return strtolower( $sign->theWord );
		} );

		return view( 'sign' )->with( array( 'word' => '#' . $theTag->tag, 'signs' => $signs, 'hashtag' => true ) );
	}

	/////////////////////////

    private function getTaggedSigns( Tag $tag ) {
        return $tag->descriptions()->get();
    }

    private function findTagByName( string $tag ): Tag {
        return Tag::where( 'tag', $tag )->first();
    }

    /**
     * @param String $text
     * @return array
     */
    private static function findTagsInText( String $text ): array {
        $tagArray = [];
        preg_match_all( config_path('wign.tagRegexp'), $text, $tagArray );

        return $tagArray['tags'];
    }
}