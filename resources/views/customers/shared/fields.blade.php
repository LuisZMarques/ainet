@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp
<div class="mb-3"style="text-align: center;" >
    <img src="{{ $customer->user->fullPhotoUrl }}" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 200px ; align-self:center">
</div>

<div class="mb-3">
    <label for="nome" class="form-label">Nome</label>
    <input type="text" name="name" id="name" class="form-control" {{ $disabledStr }} value="{{ old('name', $customer->user->name) }}">
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="text" name="email" id="email" class="form-control" {{ $disabledStr }} value="{{ old('email', $customer->user->email ) }}">
</div>
<div class="mb-3" @if(Auth::user()->id != $customer->id) hidden @endunless>
    <label for="password" class="form-label">Password</label >
    <input type="text" name="password" id="password" class="form-control" {{ $disabledStr }} value="{{ old('password', 123) }}">  
</div>
<div class="mb-3">
    <label for="nif" class="form-label">Nif</label>
    <input type="text" name="nif" id="nif" class="form-control" {{ $disabledStr }} value="{{ old('nif', $customer->nif) }}">
</div>
<div class="mb-3">
    <label for="address" class="form-label">Endereço</label>
    <input type="text" name="address" id="address" class="form-control" {{ $disabledStr }} value="{{ old('address', $customer->address) }}">
</div>
<div class="mb-3">
    <label for="default_payment_type" class="form-label">Tipo Pagamento</label>
    <select name="default_payment_type" id="default_payment_type" class="form-select" {{ $disabledStr }} >
        <option {{ old('default_payment_type', $customer->default_payment_type) ? 'selected' : '' }} value="PAYPAL">PAYPAL</option>
        <option {{ old('default_payment_type', $customer->default_payment_type) ? 'selected' : '' }} value="VISA">VISA</option>
        <option {{ old('default_payment_type', $customer->default_payment_type) ? 'selected' : '' }} value="MC">MasterCard</option>
    </select>
</div>
<div class="mb-3">
    <label for="default" class="form-label">Ref. Pagamento</label>
    <input type="text" name="default" id="default" class="form-control" {{ $disabledStr }}  value="{{ old('default', $customer->default_payment_ref) }}">
</div>
