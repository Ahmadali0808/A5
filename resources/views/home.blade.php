@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <h1>Dashboard</h1>
    <p>
        Welkom, {{ Auth::user()->name }}.
        <a href="{{ route('profile.edit') }}">Gebruikersgegevens aanpassen</a>
    </p>

   <div class="d-flex justify-content-between align-items-center mt-4">
    <h2>Mijn bands</h2>
    <a href="{{ route('bands.create') }}" class="btn btn-primary">Nieuwe band</a>
    </div>

    @if ($bands->isEmpty())
        <p>Je beheert nog geen bands.</p>
    @else
        <table class="table">
            <tbody>
            @foreach ($bands as $band)
           <tr>
    <td>{{ $band->name }}</td>
    <td>{{ $band->description }}</td>
    <td class="text-end">
        <a href="{{ route('bands.edit', $band) }}" class="btn btn-sm btn-outline-secondary">Aanpassen</a>
        <form method="POST" action="{{ route('bands.destroy', $band) }}" class="d-inline"
              onsubmit="return confirm('Weet je het zeker?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger">Verwijderen</button>
        </form>
    </td>
    </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection