@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Bands zoeken</h1>

    <form method="GET" action="{{ route('bands.index') }}" class="d-flex gap-2 mb-4">
        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Zoek op bandnaam">
        <button type="submit" class="btn btn-primary">Zoeken</button>
    </form>

    @if ($bands->isEmpty())
        <p>Geen bands gevonden.</p>
    @else
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Foto</th>
                <th>Bandnaam</th>
                <th>Beschrijving</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($bands as $band)
                <tr>
                    <td style="width: 120px">
                        @if ($band->photo)
                            <img src="{{ asset('storage/' . $band->photo) }}" alt="{{ $band->name }}"
                                 class="img-thumbnail" style="max-width: 100px">
                        @else
                            <span class="text-muted">Geen foto</span>
                        @endif
                    </td>
                    <td><a href="{{ route('bands.show', $band) }}">{{ $band->name }}</a></td>
                    <td>{{ $band->description }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection