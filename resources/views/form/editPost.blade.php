<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 29/10/2018
 * Time: 15.09
 */
$title = __( 'text.post.edit' );
@extends('layout.main')

@section('title', $title)
@section('open_graph')
    @include('layout.openGraph', ['title' => $title, 'url' => $url, 'desc' => $desc])
@stop

@section('content')
    <div class="form-group row">
        <label for="word" class="col-md-4 col-form-label text-md-right">{{ __('') }}</label>

        <div class="col-md-6">
            <input id="word" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="word" value="{{ old('word') }}" required autofocus>

            @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div
@endsection