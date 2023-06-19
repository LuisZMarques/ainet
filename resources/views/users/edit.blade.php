@extends('template.layout')

@section('titulo', 'Editar Perfil')

@section('main')

    <div class="mb-3"style="text-align: center;" >
        <img src="{{ $user->fullPhotoUrl }}" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 200px ; align-self:center">
    </div>
    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')
        @include('users.shared.fields', ['readonlyData' => false])
        <div class="text-center">
            <button type="submit" name="ok" class="btn btn-primary">Guardar Alterações</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
