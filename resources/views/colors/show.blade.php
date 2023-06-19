@extends('template.layout')

@section('titulo', 'Detalhes da Cor')

@section('main')
    <div>
        @include('colors.shared.fields', ['readonlyData' => true])
    </div>
    <div class="d-flex justify-content-between">
        <div class="d-flex">
            <a href="{{ route('colors.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
        <div class="d-flex">
            <a href="{{ route('colors.edit', $color->code) }}" class="btn btn-primary">Editar</a>
            <form method="POST" action="{{ route('colors.destroy', $color->code) }}" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta cor?')">Eliminar</button>
            </form>
        </div>
    </div>
@endsection
