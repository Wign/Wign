<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Foreigns extends Migration
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

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('word_id')->references('id')->on('words');
            $table->foreign('language_id')->references('id')->on('languages');
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->foreign('description_id')->references('id')->on('descriptions');
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('remotions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('remotion_votings', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('remotion_id')->references('id')->on('remotions');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('review_votings', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('review_id')->references('id')->on('reviews');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('words', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
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

        Schema::table('descriptions', function (Blueprint $table) {
            $table->dropForeign(['post_id', 'user_id']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['word_id', 'language_id']);
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->dropForeign(['tag_id', 'description_id']);
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'post_id']);
        });

        Schema::table('remotions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('remotion_votings', function (Blueprint $table) {
            $table->dropForeign(['remotion_id', 'user_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
        });

        Schema::table('review_votings', function (Blueprint $table) {
            $table->dropForeign(['review_id', 'user_id']);
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'post_id']);
        });

        Schema::table('words', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'language_id']);
        });
    }
}
