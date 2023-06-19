@extends('template.layout')

@section('titulo', 'Detalhes da Encomenda')

@section('main')



    @include('orders.shared.fields', ['readonlyData' => true])

    <div class="d-flex justify-content-between">
        <div class="d-flex">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
        <div class="d-flex">
            @if(Auth::user()->isAdmin() || Auth::user()->isEmployee())
                <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary">Editar</a>
            @endif
            @if(Auth::user()->isAdmin())
                <form method="POST" action="{{ route('orders.destroy', $order) }}" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta encomenda?')">Eliminar</button>
                </form>
            @endif
        </div>
    </div>
@endsection
