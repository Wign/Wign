<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForeignsTable extends Migration
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

        Schema::table('blacklist', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('descriptions', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('creator_id')->references('id')->on('users');
        });

        Schema::table('ILs', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('word_id')->references('id')->on('words');
            $table->foreign('video_id')->references('id')->on('videos');
            $table->foreign('description_id')->references('id')->on('descriptions');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('IL_id')->references('id')->on('ILs');
        });

        Schema::table('QCVs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('remotions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('remotion_votings', function (Blueprint $table) {
            $table->foreign('voter_id')->references('id')->on('users');
            $table->foreign('remotion_id')->references('id')->on('remotions');
        });

        Schema::table('request_words', function (Blueprint $table) {
            $table->foreign('word_id')->references('id')->on('words');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('review_votings', function (Blueprint $table) {
            $table->foreign('voter_id')->references('id')->on('users');
            $table->foreign('review_id')->references('id')->on('reviews');
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->foreign('description_id')->references('id')->on('descriptions');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('QCV_id')->references('id')->on('QCVs');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('words', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('language_id')->references('id')->on('languages');
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
            $table->dropForeign(['child_word_id', 'parent_word_id']);
        });

        Schema::table('blacklist', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('descriptions', function (Blueprint $table) {
            $table->dropForeign(['post_id', 'creator_id']);
        });

        Schema::table('ILs', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'post_id']);
        });

        Schema::table('QCVs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('remotions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('remotion_votings', function (Blueprint $table) {
            $table->dropForeign(['remotion_id', 'voter_id']);
        });

        Schema::table('request_words', function (Blueprint $table) {
            $table->dropForeign(['word_id', 'user_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
        });

        Schema::table('review_votings', function (Blueprint $table) {
            $table->dropForeign(['review_id', 'voter_id']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['author_id', 'word_id', 'video_id', 'description_id', 'language_id', 'IL_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['QCV_id']);
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->dropForeign(['tag_id', 'description_id']);
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['creator_id', 'post_id']);
        });

        Schema::table('words', function (Blueprint $table) {
            $table->dropForeign(['creator_id', 'language_id']);
        });
    }
}
