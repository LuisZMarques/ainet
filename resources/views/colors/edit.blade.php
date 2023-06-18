@extends('template.layout')

@section('titulo', 'Editar Cor')

@section('main')
    <form method="POST" action="{{ route('colors.update', $color->code) }}">
        @csrf
        @method('PUT')
        @include('colors.shared.fields', ['readonlyData' => false])

        <div class="text-center">
            <button type="submit" name="ok" class="btn btn-primary">Guardar Alterações</button>
            <a href="{{ route('colors.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
