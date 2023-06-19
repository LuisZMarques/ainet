@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp

<div class="mb-3" @if(Auth::user()->isAdmin()) hidden @endif>
    <label for="customer_id" class="form-label">Cliente id:</label>
    <input type="text" class="form-control" id="customer_id" name="customer_id" {{ $disabledStr }} value="{{isset($customer) ? old('customer_id', $customer->id) : ' '}}">
</div>

<div class="mb-3" @unless(Auth::user()->isAdmin()) hidden @endunless>
    @if ($tshirtImage && $tshirtImage->category)
    <label for="category_id" class="form-label">Categoria:</label>
    
        <select class="form-select" id="category_id" name="category_id" {{ $disabledStr }} >
            @if ($disabledStr == 'disabled')
                <option value="{{ $tshirtImage->category->id }}">{{ $tshirtImage->category->name }}</option>
            @else
                @foreach ($categories as $category)
                    <option {{ $tshirtImage->category_id== old('category_id', $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            @endif
        </select>
    @endif
</div>

<div class="mb-3">
    <label for="name" class="form-label">Nome:</label>
    <input type="text" class="form-control" id="name" name="name" {{ $disabledStr }} value="{{ old('name', $tshirtImage->name) }}" required>

    
</div>


<div class="mb-3">
    <label for="description" class="form-label">Descrição:</label>
    <textarea class="form-control" id="description" name="description" rows="3" {{ $disabledStr }} value="{{ old('description', $tshirtImage->description) }}" > {{ old('description', $tshirtImage->description) }}</textarea>
</div>