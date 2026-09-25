@extends('layouts.app')
@section('body-style', 'background-color: ' . $band->background_color . '; color: ' . $band->text_color . ';')
@section('content')
<div class="container">
    <div class="text-center">
        @if ($band->photo)
    <img src="{{ asset('storage/' . $band->photo) }}" alt="{{ $band->name }}" class="img-fluid rounded mb-4" style="max-height: 400px">
    @endif
        <h1>{{ $band->name }}</h1>
        <p class="lead">{{ $band->description }}</p>
    </div>

    <h2 class="mt-4">Biografie</h2>
    <p style="white-space: pre-line">{{ $band->biography }}</p>

    @if ($band->videos->isNotEmpty())
    <h2 class="mt-4">Video's</h2>
    <div class="row">
        @foreach ($band->videos as $video)
            @if ($video->embedUrl())
                <div class="col-md-6 mb-4">
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ $video->embedUrl() }}" title="YouTube video" allowfullscreen></iframe>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif
</div>
@endsection