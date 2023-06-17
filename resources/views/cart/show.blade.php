@extends('template.layout')

@section('titulo', 'Carrinho')

@section('subtitulo')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Espaço Privado</li>
        <li class="breadcrumb-item active">Carrinho</li>
    </ol>
@endsection

@section('main')
    @if (!empty($cart[0]))
        <div class="my-4">
            <div class="d-flex justify-content-center">
                <h3>Tshirts no carrinho</h3>
            </div>
            <div class="d-flex justify-content-end">
                <form method="POST" action="{{ route('cart.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger ms-3" name="clear">Limpar Carrinho</button>
                </form>
            </div>
        </div>

        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2500">
            <div class="carousel-inner">
                @php $active = true; $totalPrice = 0; @endphp
                    @foreach ($cart[0] as $tshirtUniqueId)
                        @php
                            $item = $cart[$tshirtUniqueId];
                            $totalPrice += $item['subTotal'];
                        @endphp
                            <div class="carousel-item {{ $active ? 'active' : '' }}">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img src="{{ $item['tshirtPreviewImage'] }}" class="card-img-top" alt="Preview tshirt" style="width: 275px; height: 300px;">
                                        <h5 class="card-title">{{ $item['imageName'] }}</h5>
                                        <p class="card-text">Tamanho: {{ $item['size'] }}</p>
                                        <p class="card-text">Quantidade: {{ $item['quantity'] }}</p>
                                        <p class="card-text">Preço Unitário: {{ $item['unitPrice'] }} €</p>
                                        <p class="card-text">Subtotal: {{ $item['subTotal'] }} €</p>
                                        <form method="POST" action="{{ route('cart.remove', $tshirtUniqueId) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Remover</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @php $active = false;@endphp
                    @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="my-4">
            <div class="text-center">
                <h5>Preço Total: {{ $totalPrice }} €</h5>

                <h3>Dados da Encomenda:</h3>
            </div>


            <form method="POST" action="{{ route('cart.store') }}">
                @csrf

                <input type="hidden" name="totalPrice" value="{{$totalPrice}}">
                <div class="mb-3">
                    <label for="nif" class="form-label">NIF</label>
                    <input type="text" class="form-control" id="nif" name="nif" value="{{$customer->nif}}" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Endereço</label>
                    <textarea class="form-control" id="address" name="address" required>{{$customer->address}}</textarea>
                </div>

                <div class="mb-3">
                    <label for="payment_type" class="form-label">Tipo de Pagamento</label>
                    <select class="form-control" id="payment_type" name="payment_type" required>
                        <option value="VISA">VISA</option>
                        <option value="PAYPAL">PayPal</option>
                        <option value="MC">MasterCard</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="payment_ref" class="form-label">Referência de Pagamento</label>
                    <textarea type="text" class="form-control" id="payment_ref" name="payment_ref" required>{{$customer->default_payment_ref}}</textarea>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notas</label>
                    <textarea type="text" class="form-control" id="notes" name="notes"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Confirmar Encomenda</button>
            </form>
        </div>
    @else
        <div>
            <h3> Não há tshirts no carrinho </h3>
        </div>
    @endif
@endsection
