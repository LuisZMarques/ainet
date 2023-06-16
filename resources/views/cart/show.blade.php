@extends('template.layout')

@section('titulo', 'Carrinho')

@section('subtitulo')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Espaço Privado</li>
        <li class="breadcrumb-item active">Carrinho</li>
    </ol>
@endsection

@section('main')
    @if ($cart)
        <div>
            <h3>Tshirts no carrinho</h3>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Tshirt</th>
                    <th scope="col">Cor</th>
                    <th scope="col">Tamanho</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Preço Unitário</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart->items as $item)
                    <tr>
                        <td>{{ $item->tshirt->name }}</td>
                        <td>{{ $item->color->name }}</td>
                        <td>{{ $item->size }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit_price }} €</td>
                        <td>{{ $item->subtotal }} €</td>
                        <td>
                            <form method="POST" action="{{ route('cart.destroy', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Remover</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5"></td>
                    <td><strong>Total</strong></td>
                    @php
                        $total = 0;
                        foreach ($cart->items as $item) {
                            $total += $item->subtotal;
                        }
                        session('total', $total);
                    @endphp
                    <td><strong>{{ $total }} €</strong></td>
                </tr>
            </tbody>

        <div class="my-4 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" name="ok" form="formStore">Confirmar Inscrições</button>
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
