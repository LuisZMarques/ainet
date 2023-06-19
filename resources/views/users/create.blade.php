@extends('template.layout')

@section('titulo', 'Criar novo Utlizador')

@section('main')
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        @include('users.shared.fields')

        <div class="text-center">
            <button type="submit" name="ok" class="btn btn-primary">Guardar Utlizador</button>
            <a href="{{ route('colors.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
