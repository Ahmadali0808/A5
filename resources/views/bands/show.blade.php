@extends('layouts.app')

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

    {{-- Hier komen in stap 13 de video's --}}
</div>
@endsection