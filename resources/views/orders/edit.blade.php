@extends('template.layout')

@section('titulo', 'Editar Encomenda')

@section('main')
    <form method="POST" action="{{ route('orders.update', $order->id) }}">
        @csrf
        @method('PUT')
        @include('orders.shared.fields', ['readonlyData' => false])
        
        <div class="text-center">
            <button type="submit" name="ok" class="btn btn-primary">Guardar Alterações</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
