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

    <h2 class="mt-4">Mijn bands</h2>

    @if ($bands->isEmpty())
        <p>Je beheert nog geen bands.</p>
    @else
        <table class="table">
            <tbody>
            @foreach ($bands as $band)
                <tr>
                    <td>{{ $band->name }}</td>
                    <td>{{ $band->description }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection