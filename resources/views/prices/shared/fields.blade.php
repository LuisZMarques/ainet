@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp
<div class="mb-3">
    <label for="unit_price_catalog" class="form-label">Preço T-Shirt Catálogo</label>
    <input type="text" class="form-control @error('unit_price_catalog') is-invalid @enderror" id="unit_price_catalog" name="unit_price_catalog" {{ $disabledStr }} value="{{ $price->unit_price_catalog }}">
    @error('unit_price_catalog')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>


<div class="mb-3">
    <label for="unit_price_own" class="form-label">Preço T-Shirt Própria</label>
    <input type="text" class="form-control @error('unit_price_own') is-invalid @enderror" id="unit_price_own" name="unit_price_own" {{ $disabledStr }} value="{{ $price->unit_price_own }}">
    @error('unit_price_own')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>


<div class="mb-3">
    <label for="unit_price_catalog_discount" class="form-label">Preço T-Shirt Catálogo com Desconto</label>
    <input type="text" class="form-control @error('unit_price_catalog_discount') is-invalid @enderror" id="unit_price_catalog_discount" name="unit_price_catalog_discount" {{ $disabledStr }} value="{{ $price->unit_price_catalog_discount }}">
    @error('unit_price_catalog_discount')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>


<div class="mb-3">
    <label for="unit_price_own_discount" class="form-label">Preço T-Shirt Própria com Desconto</label>
    <input type="text" class="form-control @error('unit_price_own_discount') is-invalid @enderror" id="unit_price_own_discount" name="unit_price_own_discount" {{ $disabledStr }} value="{{ $price->unit_price_own_discount }}">
    @error('unit_price_own_discount')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>


<div class="mb-3">
    <label for="qty_discount" class="form-label">Quantidade de Desconto</label>
    <input type="text" class="form-control @error('qty_discount') is-invalid @enderror" id="qty_discount" name="qty_discount" {{ $disabledStr }} value="{{ $price->qty_discount }}">
    @error('qty_discount')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
