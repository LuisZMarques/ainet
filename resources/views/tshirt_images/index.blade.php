@extends('template.layout')

@section('titulo', 'Lista de Imagens de T-Shirts da Loja')

@section('main')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <a href="{{ route('tshirt_images.create') }}" class="btn btn-primary mb-3">Nova Imagem de Tshirt</a>
            <form action="{{ route('tshirt_images.index') }}" method="GET" class="search-bar mb-3 d-flex">
                <select id="status-select" class="form-control" style="width:20%" name="category">
                    <option value="">Todas as Categorias</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                </select>
                <input type="text" id="search-input" placeholder="Procura por Nome" class="form-control" name="search">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
            <div class="table-responsive">
                <table class="table table-dark table-striped" style="background-color: #f1f1f1;">
                    <thead>
                        <tr>   
                            <th>Imagem</th>
                            <th>Categoria</th>
                            <th>Nome</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tshirtImages as $tshirtImage)
                            <tr>
                                <td> <img src="{{ asset('storage/tshirt_images/' . $tshirtImage->image_url) }}" alt="{{ $tshirtImage->name }}" style="width: 33px; height: 33px;"></td>
                                <td>{{ $tshirtImage->category_id ? $tshirtImage->category_id : 'Nenhuma' }}</td>
                                <td>{{ $tshirtImage->name }}</td>
                                <td>
                                    <a href="{{ route('tshirt_images.show', $tshirtImage->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('tshirt_images.edit', $tshirtImage->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                    <form method="POST" action="{{ route('tshirt_images.destroy', $tshirtImage->id) }}" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta imagem de T-Shirt?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $tshirtImages->links() }}
        </div>
    </div>
@endsection
