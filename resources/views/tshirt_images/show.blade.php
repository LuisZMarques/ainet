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
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('tshirt_images.index') }}" class="btn btn-secondary">Voltar</a>
                @elseif (Auth::user()->isCustomer())
                    <a href="{{ route('tshirt_images.minhas') }}" class="btn btn-secondary">Voltar</a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar</a>
                @endif
            </div>
            <div class="d-flex">
                <a href="{{ route('tshirt_images.edit', $tshirtImage->id ) }}" class="btn btn-primary">Editar</a>
                <form method="POST" action="{{ route('tshirt_images.destroy', $tshirtImage->id) }}" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta imagem de T-Shirt?')">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
