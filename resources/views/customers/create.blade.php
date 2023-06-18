@extends('template.layout')

@section('titulo', 'Criar novo Cliente')

@section('main')
    <form method="POST" action="{{ route('customers.store') }}">
        @csrf
        @include('customers.shared.fields')

        <div class="text-center">
            <button type="submit" name="ok" class="btn btn-primary">Guardar Cliente</button>
            <a href="{{ route('colors.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
