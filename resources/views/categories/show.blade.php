@extends('template.layout')

@section('titulo', 'Detalhes da Categoria')

@section('main')
    <div>
        @include('categories.shared.fields', ['readonlyData' => true])
    </div>
    <div class="d-flex justify-content-between">
        <div class="d-flex">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
        <div class="d-flex">
            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary">Editar</a>
            <form method="POST" action="{{ route('categories.destroy', $category->id) }}" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">Eliminar</button>
            </form>
        </div>
    </div>
@endsection
