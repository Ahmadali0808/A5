@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <h1>{{ $band->name }} aanpassen</h1>

    <form method="POST" action="{{ route('bands.update', $band) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('bands._form')
        <button type="submit" class="btn btn-primary">Opslaan</button>
        <a href="{{ route('bands.show', $band) }}" class="btn btn-outline-secondary">EPK bekijken</a>
    </form>

    <hr class="my-5">

    <h2>Beheerders</h2>
    <ul>
        @foreach ($band->users as $user)
            <li>{{ $user->name }} ({{ $user->email }})</li>
        @endforeach
    </ul>

    <form method="POST" action="{{ route('bands.users.add', $band) }}" class="d-flex gap-2">
        @csrf
        <input type="email" name="email" class="form-control" placeholder="E-mail van een andere gebruiker" required>
        <button type="submit" class="btn btn-secondary">Beheerder toevoegen</button>
    </form>
</div>
@endsection