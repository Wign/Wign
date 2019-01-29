<?php

namespace App\Services;

use App\Description;
use App\Post;
use App\Tag;
use URL;

//define( 'REGEXP', config( 'wign.tagRegexp' ) );

/**
 * Class TagService
 * @package App\Services
 * @deprecated
 */
class TagService {

    /**
     * @return Tag[]|\Illuminate\Database\Eloquent\Collection
     * @deprecated
     */
	public function getAllTags() {
		return Tag::all();
	}

    /**
     * @param int $id
     * @return Tag
     * @deprecated
     */
	public function findTagByID( int $id ): Tag {
		return Tag::find( $id );
	}

    /**
     * @param string $search
     * @return Tag[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @deprecated
     */
	public function getQueriedTags( string $search ) {
		return Tag::getQueriedTag($search)->get();
	}

}