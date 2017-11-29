<?php
    if($word) { $titel = ucfirst($word->word); }
?>

@extends('layout.main')

@section('content')

<h1>Rapportering af {{ $titel }}</h1>
@if (count($errors) > 0)
    <div class="alert alert-danger">
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
        <option value="Ikke tegn">Indholder ikke tegn</option>
        <option value="Seksuelt">Seksuelt indhold</option>
        <option value="Voldeligt">Voldeligt eller frastødende indhold</option>
        <option value="Groft">Modbydeligt eller groft indhold</option>
        <option value="Skadelige">Skadelige, farlige handlinger</option>
        <option value="Børnemishandling">Børnemishandling</option>
        <option value="Spam">Spam eller vildledende</option>
        <option value="Rettighedskrænkende">Krænker mine rettigheder</option>
        <option value="Andet">Andet</option>
    </select>
    <label for="commentar">Skriv venligst ydereligere oplysninger om årsagen</label>
    <textarea class="" name="commentar"></textarea>
    <label for="email">Din email (så vi kan kontakte dig for ydereligere oplysninger)</label>
    <input type="text" name="email">
    <input type="hidden" name="id" value="{{ $id }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" class="flag-submit" value="Send">
</form>

@include('layout.menu')

@stop