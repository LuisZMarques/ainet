@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp

<div class="mb-3">
    <label for="nome" class="form-label">Nome</label>
    <input type="text" name="name" id="name" class="form-control" {{ $disabledStr }} value="{{ old('name', $user->name) }}">
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="text" name="email" id="email" class="form-control" {{ $disabledStr }} value="{{ old('email', $user->email ) }}">
</div>
<div class="mb-3" @if(Auth::user()->id != $user->id) hidden @endunless>
    <label for="password" class="form-label">Password</label >
    <input type="text" name="password" id="password" class="form-control" {{ $disabledStr }} value="{{ old('password', 123) }}">  
</div>
<div class="mb-3">
    <label for="user_type" class="form-label">Tipo de Usuário</label>
    <select name="user_type" id="user_type" class="form-control" {{ $disabledStr }}>
        <option value="A" {{ old('user_type', $user->user_type) === 'Administrador' ? 'selected' : '' }}>Administrador</option>
        <option value="E" {{ old('user_type', $user->user_type) === 'Empregado' ? 'selected' : '' }}>Empregado</option>
    </select>
</div>
<div class="mb-3">
    <div class="form-check">
    <input type="hidden" name="blocked" value="0"> 
        <input type="checkbox" name="blocked" id="blocked" class="form-check-input" value="1" {{ $disabledStr }} {{ old('blocked', $user->blocked) ? 'checked' : '' }}>
        <label class="form-check-label" for="blocked">Bloqueado</label>
    </div>
</div>
