<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class LikeController extends Controller
{
    /**
     * Insert a like of the sign into database, linked to the IP address of the user.
     * It utilize the POST input to gain the sign id.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse a JSON response with status,
     * message and resulting number of signs if it succeed
     */
    public function create( Request $request ) {
        $post = $request->input('post');
        $user = \Auth::user();
        $like = $post->video->likes()->attach($user);

        if ( $like ) {
            $response = array(
                'status' => 'success',
                'msg'    => __('flash.like.insert.success'),
                'likes'  => $post->video->likes()->count(),
            );
        } else {
            $response = array(
                'status' => 'failed',
                'msg'    => __('flash.like.insert.failed')
            );
        }

        return Response::json( $response );
    }

    /**
     * Removes a like  of the sign from database, linked to the IP address of the user.
     * It utilize the POST input to gain the sign id.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse a JSON response with status,
     * message and resulting number of signs if it succeed
     * @throws \Exception
     */
    public function delete( Request $request ) {
        $post = $request->input( 'post' );
        $user = \Auth::user();
        $like = $post->video->likes()->detach($user);

        if ( $like ) {
            $response = array(
                'status' => 'success',
                'msg'    => __('flash.like.delete.success'),
                'likes'  => $post->video->likes()->count(),
            );
        } else {
            $response = array(
                'status' => 'failed',
                'msg'    => __('flash.like.delete.failed')
            );
        }

        return Response::json( $response );
    }
}
