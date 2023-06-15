@extends('template.layout')

@section('titulo', 'Detalhes da Encomenda')

@section('main')
    @include('orders.shared.fields')

    <div class="d-flex justify-content-between">
        <div class="d-flex">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
        <div class="d-flex">
            <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary">Editar</a>
            <a href="{{ route('orders.destroy', $order) }}" class="btn btn-danger">Eliminar</a>
        </div>
    </div>
@endsection
