@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp
<div class="mb-3"style="text-align: center;" >
    <img src="{{ $customer->user->fullPhotoUrl }}" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 200px ; align-self:center">
</div>

<div class="mb-3">
    <label for="nome" class="form-label">Nome</label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" {{ $disabledStr }} value="{{ old('name', $customer->user->name) }}">
</div>
@error('name')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror" {{ $disabledStr }} value="{{ old('email', $customer->user->email ) }}">
</div>
@error('email')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror

<div class="mb-3" @if(Auth::user()->id != $customer->id) hidden @endunless>
    <label for="password" class="form-label">Password</label >
    <input type="text" name="password" id="password" class="form-control @error('password') is-invalid @enderror" {{ $disabledStr }} value="{{ old('password', 123) }}">  
</div>
@error('password')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror

<div class="mb-3">
    <label for="nif" class="form-label">Nif</label>
    <input type="text" name="nif" id="nif" class="form-control @error('nif') is-invalid @enderror" {{ $disabledStr }} value="{{ old('nif', $customer->nif) }}">
</div>
@error('nif')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror

<div class="mb-3">
    <label for="address" class="form-label">Endereço</label>
    <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" {{ $disabledStr }} value="{{ old('address', $customer->address) }}">
</div>
@error('address')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror

<div class="mb-3">
    <label for="default_payment_type" class="form-label">Tipo Pagamento</label>
    <select name="default_payment_type" id="default_payment_type" class="form-select @error('default_payment_type') is-invalid @enderror" {{ $disabledStr }} >
        <option {{ old('default_payment_type', $customer->default_payment_type) ? 'selected' : '' }} value="PAYPAL">PAYPAL</option>
        <option {{ old('default_payment_type', $customer->default_payment_type) ? 'selected' : '' }} value="VISA">VISA</option>
        <option {{ old('default_payment_type', $customer->default_payment_type) ? 'selected' : '' }} value="MC">MasterCard</option>
    </select>
</div>
@error('default_payment_type')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror

<div class="mb-3">
    <label for="default_payment_ref" class="form-label">Ref. Pagamento</label>
    <input type="text" name="default_payment_ref" id="default_payment_ref" class="form-control @error('default_payment_ref') is-invalid @enderror" {{ $disabledStr }}  value="{{ old('default', $customer->default_payment_ref) }}">
</div>
@error('default_payment_ref')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror
