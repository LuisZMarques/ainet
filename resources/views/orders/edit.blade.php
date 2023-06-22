@extends('template.layout')

@section('titulo', 'Editar Encomenda')

@section('main')
    <form method="POST" action="{{ route('orders.update', $order->id) }}">
        @csrf
        @method('PUT')
        @include('orders.shared.fields', ['readonlyData' => false])
        
        <div class="text-center">
            <button type="submit" name="ok" class="btn btn-primary">Guardar Alterações</button>
            @if(Auth::user()->isAdmin() || Auth::user()->isEmployee())
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancelar</a>
            @elseif(Auth::user()->isCustomer())
                <a href="{{ route('orders.minhas') }}" class="btn btn-secondary">Cancelar</a>
            @else
                <a href="{{ route('home') }}" class="btn btn-secondary">Cancelar</a>
            @endif
        </div>
    </form>
@endsection
