@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp
<div class="mb-3">
    <label for="code" class="form-label">Código:</label>
    <input type="text" class="form-control" id="code" name="code" disabled value="{{ old('code', $color->code) }}"required>
</div>
<div class="mb-3">
    <label for="name" class="form-label">Nome:</label>
    <input type="text" class="form-control" id="name" name="name" {{ $disabledStr }} value="{{ old('name', $color->name) }}" required>
</div>
