@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center">
        {{-- Hier komt in stap 12 de foto --}}

        <h1>{{ $band->name }}</h1>
        <p class="lead">{{ $band->description }}</p>
    </div>

    <h2 class="mt-4">Biografie</h2>
    <p style="white-space: pre-line">{{ $band->biography }}</p>

    {{-- Hier komen in stap 13 de video's --}}
</div>
@endsection