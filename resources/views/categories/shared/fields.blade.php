@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp
<div class="mb-3">
    <label for="inputName" class="form-label">Nome:</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputName" name="name" {{ $disabledStr }} value="{{ old('name', $category->name) }}" required>
</div>
@error('name')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror