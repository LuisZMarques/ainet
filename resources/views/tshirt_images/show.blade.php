@extends('template.layout')

@section('titulo', 'Detalhes da Imagem de T-Shirt')

@section('main')
    <div>
        <div class="mb-3"style="text-align: center;" >
            <img src="{{ asset('storage/tshirt_images/' . $tshirtImage->image_url) }}" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 200px ; align-self:center">
        </div>

        @include('tshirt_images.shared.fields', ['readonlyData' => true])
        
        <div class="d-flex justify-content-between">
            <div class="d-flex">
                <a href="{{ route('tshirt_images.index') }}" class="btn btn-secondary">Voltar</a>
            </div>
            <div class="d-flex">
                <a href="{{ route('tshirt_images.edit', $tshirtImage->id ) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('tshirt_images.destroy', $tshirtImage->id ) }}" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
@endsection
