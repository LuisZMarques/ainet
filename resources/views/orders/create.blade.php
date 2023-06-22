@extends('template.layout')

@section('titulo', 'Criar nova Encomenda')

@section('main')
    <div class="container">
        <form method="POST" action="{{ route('orders.store') }}">
            @csrf
            @include('orders.shared.fields')
            <div class="text-center"
                <button type="submit" name="ok">Guardar Encomenda</button>
                @if(Auth::user()->isAdmin() || Auth::user()->isEmployee())
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Voltar</a>
                @elseif(Auth::user()->isCustomer())
                    <a href="{{ route('orders.minhas') }}" class="btn btn-secondary">Voltar</a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar</a>
                @endif
            </div>
        </form>
    </div>
@endsection
