<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForeignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aliases', function (Blueprint $table) {
            $table->foreign('child_word_id')->references('id')->on('words');
            $table->foreign('parent_word_id')->references('id')->on('words');
        });

        Schema::table('descriptions', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('ils', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('qcvs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('remotions', function (Blueprint $table) {
            $table->foreign('qcv_id')->references('id')->on('qcvs');
        });

        Schema::table('remotion_votings', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('remotion_id')->references('id')->on('remotions');
        });

        Schema::table('request_words', function (Blueprint $table) {
            $table->foreign('word_id')->references('id')->on('words');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('il_id')->references('id')->on('ils');
        });

        Schema::table('review_votings', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('review_id')->references('id')->on('reviews');
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->foreign('description_id')->references('id')->on('descriptions');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('wordlinks', function (Blueprint $table) {
            $table->foreign('word_id')->references('id')->on('words');
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aliases', function (Blueprint $table) {
            $table->dropForeign(['child_word_id']);
            $table->dropForeign(['parent_word_id']);
        });

        Schema::table('descriptions', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('ils', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['post_id']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('qcvs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('remotions', function (Blueprint $table) {
            $table->dropForeign(['qcv_id']);
        });

        Schema::table('remotion_votings', function (Blueprint $table) {
            $table->dropForeign(['remotion_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('request_words', function (Blueprint $table) {
            $table->dropForeign(['word_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['il_id']);
        });

        Schema::table('review_votings', function (Blueprint $table) {
            $table->dropForeign(['review_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->dropForeign(['description_id']);
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['post_id']);
        });

        Schema::table('wordlinks', function (Blueprint $table) {
            $table->dropForeign(['word_id']);
            $table->dropForeign(['post_id']);
            $table->dropForeign(['user_id']);
        });
    }
}
