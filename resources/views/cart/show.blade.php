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
        <div>
            <h3>Tshirts no carrinho</h3>
        </div>
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @php $active = true; @endphp
                @foreach ($cart[0] as $tshirtUniqueId)
                    @php $item = $cart[$tshirtUniqueId]; @endphp
                    <div class="carousel-item {{ $active ? 'active' : '' }}">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $tshirtUniqueId }}</h5>
                                <p class="card-text">Cor: {{ $item['color'] }}</p>
                                <p class="card-text">Tamanho: {{ $item['size'] }}</p>
                                <p class="card-text">Quantidade: {{ $item['quantity'] }}</p>
                                <p class="card-text">Preço Unitário: {{ $item['unitPrice'] }} €</p>
                                <p class="card-text">Subtotal: {{ $item['subTotal'] }} €</p>
                                <form method="POST" action="{{ route('cart.destroy', $item['imageId']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Remover</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @php $active = false; @endphp
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

        <div class="my-4 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" name="ok" form="formStore">Confirmar Compra</button>
            <button type="submit" class="btn btn-danger ms-3" name="clear" form="formClear">Limpar Carrinho</button>
        </div>
        <form id="formStore" method="POST" action="{{ route('cart.store') }}" class="d-none">
            @csrf
        </form>
        <form id="formClear" method="POST" action="{{ route('cart.destroy') }}" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endif
    <div>
        <h3> Não há tshirts no carrinho </h3>
    </div>
@endsection
