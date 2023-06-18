@extends('template.layout')

@section('titulo', 'Criar Nova Cor')

@section('main')
    <form method="POST" action="{{ route('colors.store') }}">
        @csrf
        @include('colors.shared.fields')

        <div class="text-center">
            <button type="submit" name="ok" class="btn btn-primary">Guardar Cor</button>
            <a href="{{ route('colors.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
