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