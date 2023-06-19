@extends('template.layout')

@section('titulo', 'Perfil de Cliente')

@section('main')
    
    <div class="mb-3"style="text-align: center;" >
        <img src="{{ $user->fullPhotoUrl }}" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 200px ; align-self:center">
    </div>
    @include('users.shared.fields', ['readonlyData' => true])

    <div class="d-flex justify-content-between">
        <div class="d-flex">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
        <div class="d-flex">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Editar</a>
            <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Eliminar</button>
            </form>
        </div>
    </div>
@endsection
