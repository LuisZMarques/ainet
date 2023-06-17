@extends('template.layout')

@section('titulo', 'Catalogo de T-Shirts')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('tshirt_images.catalogo') }}" method="GET" class="search-bar mb-3 d-flex">
                    <select id="status-select" class="form-control" style="width:20%" name="category">
                        <option value="">Todas as Categorias</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                    </select>
                    <input type="text" id="search-input" placeholder="Procura por Nome" class="form-control" name="search">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>
                <div class="row">
                    @foreach($tshirtImages as $tshirtImage)
                        <div class="col-md-4 items-center">
                            <div class="card mb-4 text-center align-items-center">
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="idImage" value="{{ $tshirtImage->id }}">
                                <img src="{{ asset('storage/tshirt_images/' . $tshirtImage->image_url) }}" class="card-img-top m-2" alt="T-Shirt Image" style="width: 150px; height: 150px;">
                                <div class="card-body">
                                    <input type="hidden" name="nameImage" value="{{ $tshirtImage->name }}">
                                    <h5 class="card-title">{{ $tshirtImage->name }}</h5>
                                    <select class="form-control m-2" name="color" required>
                                        <option value="">Seleciona a cor</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->code }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <select class="form-control m-2"  name="size" required>
                                        <option value="">Seleciona o tamanho</option>
                                        <option value="XS">XS</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                    </select>
                                    <select class="form-control m-2" name="quantity" required>
                                        <option value="">Seleciona a quantidade</option>
                                        @for($i=1; $i <= 9; $i++)
                                        @php
                                            if ($prices->qty_discount >= $i) {
                                                $unitPrice = $prices->unit_price_catalog;
                                            } else {
                                                $unitPrice = $prices->unit_price_catalog_discount;
                                            }
                                            $subtotal = $unitPrice * $i;
                                        @endphp
                                        <option value="{{ $i }}">{{ $i }} ({{ $subtotal }} €)</option>
                                        @endfor
                                    </select>
                                    <input type="hidden" name="unitPrice" value="{{ $unitPrice }}">
                                    <input type="hidden" name="subTotal" value="{{ $subtotal }}">
                                    <button type="submit" name="addToCart" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                        Adicionar ao Carrinho
                                    </button>
                                </div>
                            </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            {{ $tshirtImages->links() }}
            </div>
        </div>
    </div>
@endsection