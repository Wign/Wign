<?php
$contentTypes = [
	"Ikke tegn"           => "Indholder ikke tegn",
	"Tegnduplikat"        => "Indholder duplikat af et andet tegn",
	"Seksuelt"            => "Seksuelt indhold",
	"Voldeligt"           => "Voldeligt eller frastødende indhold",
	"Groft"               => "Modbydeligt eller groft indhold",
	"Skadelige"           => "Skadelige, farlige handlinger",
	"Børnemishandling"    => "Børnemishandling",
	"Spam"                => "Spam eller vildledende",
	"Rettighedskrænkende" => "Krænker mine rettigheder",
	"Andet"               => "Andet",
];
?>

@extends('layout.main')

@section('content')

    <h1>Rapportering af {{ $word->word ?? "et tegn" }}</h1>
    @if (count($errors) > 0)
        <div class="msg--flash">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <img src="{{ $img }}">

    <form id="flagSign" method="POST" action="{{ URL::action('SignController@flagSign') }}">
        <label for="content">Hvad rapporter du videoen for?</label>
        <select name="content">
            @foreach($contentTypes as $key => $text)
                <option value="{{ $key }}" {{ old('content') == $key ? "selected" : "" }}>{{ $text }}</option>
            @endforeach
        </select>
        <label for="commentar">Skriv venligst ydereligere oplysninger om årsagen</label>
        <textarea class="" name="commentar">{{ old( 'commentar' ) }}</textarea>
        <label for="email">Din email (så vi kan kontakte dig for ydereligere oplysninger)</label>
        <input type="text" name="email" value="{{ old('email') }}">
        <input type="hidden" name="id" value="{{ $id }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="flag-submit" value="Send">
    </form>

    @include('layout.menu')

@stop