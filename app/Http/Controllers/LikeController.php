<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class LikeController extends Controller
{
    /**
     * Insert a vote (like) of the sign into database, linked to the IP address of the user.
     * It utilize the POST input to gain the sign id.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse a JSON response with status,
     * message and resulting number of signs if it succeed
     */
    public function createLike( Request $request ) {

        $post = $request->input('post');
        $user = \Auth::user();

        $vote = $post->likes()->attach($user);

        if ( $vote ) {

            $response = array(
                'status' => 'success',
                'msg'    => __('flash.vote.insert.success'),
                'votes'  => $post->likes()->count(),
            );
        } else {
            $response = array(
                'status' => 'failed',
                'msg'    => __('flash.vote.insert.failed')
            );
        }

        return Response::json( $response );
    }

    /**
     * Removes a vote (like) of the sign from database, linked to the IP address of the user.
     * It utilize the POST input to gain the sign id.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse a JSON response with status,
     * message and resulting number of signs if it succeed
     * @throws \Exception
     */
    public function deleteLike( Request $request ) {
        $post = $request->input( 'post' );
        $user = \Auth::user();

        $vote = $post->likes()->detach($user);

        if ( $vote ) {

            $response = array(
                'status' => 'success',
                'msg'    => __('flash.vote.delete.success'),
                'votes'  => $post->likes()->count(),
            );
        } else {
            $response = array(
                'status' => 'failed',
                'msg'    => __('flash.vote.delete.failed')
            );
        }

        return Response::json( $response );
    }
}
