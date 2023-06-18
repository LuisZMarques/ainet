@extends('template.layout')

@section('titulo', 'Editar Imagem de T-Shirt')

@section('main')
    <form method="POST" action="{{ route('tshirt_images.update', $tshirtImage->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3"style="text-align: center;" >
            <div>
                <img src="{{ asset('storage/tshirt_images/' . $tshirtImage->image_url) }}" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 200px ; align-self:center">
            </div>
            <div>
                <button type="button" class="btn btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Upload Nova Imagem #TODO
                </button>
            </div>
        </div>


        @include('tshirt_images.shared.fields', ['readonlyData' => false])

        <div class="text-center">
            <button type="submit" name="ok" class="btn btn-primary">Guardar Alterações</button>
            <a href="{{ route('tshirt_images.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
