@extends('template.layout')

@section('titulo', 'Criar Nova Imagem de T-Shirt')

@section('main')
    <form method="POST" action="{{ route('tshirt_images.store') }}">
        @csrf
        @include('tshirt_images.shared.fields', ['categories' => $categories])

        <div class="text-center">
            <button type="submit" name="ok" class="btn btn-primary">Guardar Imagem de T-Shirt</button>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('tshirt_images.index') }}" class="btn btn-secondary">Cancelar</a>
            @elseif (Auth::user()->isCustomer())
                <a href="{{ route('tshirt_images.minhas') }}" class="btn btn-secondary">Cancelar</a>
            @else
                <a href="{{ route('home') }}" class="btn btn-secondary">Cancelar</a>
            @endif
        </div>
    </form>
@endsection
