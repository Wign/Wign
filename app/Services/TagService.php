<?php

namespace App\Services;

use App\Description;
use App\Post;
use App\Tag;
use URL;

//define( 'REGEXP', config( 'wign.tagRegexp' ) );

class TagService {

	public function getAllTags() {
		return Tag::all();
	}

	public function findTagByID( int $id ): Tag {
		return Tag::find( $id );
	}



	public function getQueriedTags( string $search ) {
		return Tag::getQueriedTag($search)->get();
	}

}