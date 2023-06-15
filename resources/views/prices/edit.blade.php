@extends('template.layout')

@section('titulo', 'Editar Preços da Loja')

@section('main')
    <form method="POST" action="{{ route('prices.update', $price->id) }}">
        @csrf
        @method('PUT')
        @include('prices.shared.fields', ['readonlyData' => false])

        <div>
            <button type="submit" class="btn btn-primary">Guardar Alterações</button>
            <a href="{{ route('prices.index') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
@endsection
