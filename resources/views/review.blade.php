<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 05/11/2018
 * Time: 21.47
 */
?>
@extends('layout.main')
@section('open_graph')
    @include('layout.openGraph')
@stop
@section('extra_head_scripts')
    @include('layout.cameratag')
@endsection

@section('content')
    @include('partials.evaluatePost')
    <form method="POST" action="{{ route('review.update', ['id' => $review->id])}}">
        @csrf
        @include('partials.decision')
    </form>

@endsection


