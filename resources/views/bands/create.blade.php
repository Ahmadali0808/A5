@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nieuwe band</h1>

    <form method="POST" action="{{ route('bands.store') }}" enctype="multipart/form-data">
        @csrf
        @include('bands._form')
        <button type="submit" class="btn btn-primary">Opslaan</button>
    </form>
</div>
@endsection