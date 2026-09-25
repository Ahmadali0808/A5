@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label for="name" class="form-label">Bandnaam</label>
    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $band->name) }}" required>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Korte beschrijving</label>
    <input type="text" id="description" name="description" class="form-control" value="{{ old('description', $band->description) }}" required>
</div>

<div class="mb-3">
    <label for="biography" class="form-label">Biografie</label>
    <textarea id="biography" name="biography" rows="6" class="form-control">{{ old('biography', $band->biography) }}</textarea>
</div>

<div class="mb-3">
    <label for="photo" class="form-label">Foto</label>
    @if ($band->photo)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $band->photo) }}" alt="Huidige foto" style="max-width: 150px">
        </div>
    @endif
    <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
</div>
<div class="row mb-3">
    <div class="col">
        <label for="background_color" class="form-label">Achtergrondkleur</label>
        <input type="color" id="background_color" name="background_color"
               class="form-control form-control-color"
               value="{{ old('background_color', $band->background_color ?? '#ffffff') }}">
    </div>
    <div class="col">
        <label for="text_color" class="form-label">Tekstkleur</label>
        <input type="color" id="text_color" name="text_color"
               class="form-control form-control-color"
               value="{{ old('text_color', $band->text_color ?? '#000000') }}">
    </div>
</div>
<h5 class="mt-4">YouTube video's (maximaal {{ \App\Models\Band::MAX_VIDEOS }})</h5>
@for ($i = 0; $i < \App\Models\Band::MAX_VIDEOS; $i++)
    <div class="mb-2">
        <input type="url" name="videos[{{ $i }}]" class="form-control"
               placeholder="https://www.youtube.com/watch?v=..."
               value="{{ old('videos.' . $i, $band->videos[$i]->youtube_url ?? '') }}">
    </div>
@endfor