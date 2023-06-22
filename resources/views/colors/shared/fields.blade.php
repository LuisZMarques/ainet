@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp
<div class="mb-3">
    <label for="code" class="form-label">Código:</label>
    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" {{ $disabledStr }} value="{{ old('code', $color->code) }}">
    @error('code')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label for="name" class="form-label">Nome:</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" {{ $disabledStr }} value="{{ old('name', $color->name) }}" >
    @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
