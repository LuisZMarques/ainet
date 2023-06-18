@extends('template.layout')

@section('titulo', 'Criar nova Encomenda')

@section('main')
    <div class="container">
        <form method="POST" action="{{ route('orders.store') }}">
            @csrf
            @include('orders.shared.fields')
            <div class="text-center"
                <button type="submit" name="ok">Guardar Encomenda</button>
            </div>
        </form>
    </div>
@endsection
