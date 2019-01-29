@extends('layout.main')
@section('open_graph')
    @include('layout.openGraph')
@stop
@section('extra_head_scripts')
    @include('layout.cameratag')
@endsection

@section('content')
    @include('partials.evaluateUser')
    <form method="POST" action="{{ route('admin.remotion', ['id' => $remotion->id])}}">
        @csrf

       @include('partials.decision')
    </form>
@endsection
