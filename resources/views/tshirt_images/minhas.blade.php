@extends('template.layout')

@section('titulo', 'Imagens das Camisas do Cliente ' . $customer->user->name)

@section('main')
    <a href="{{ route('tshirt_images.create') }}" class="btn btn-primary mb-3">Nova Imagem de Tshirt</a>
    <div class="table-responsive">
        <table class="table table-dark table-bordered text-center">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>URL da Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customer->tshirtImages as $tshirtImage)
                    <tr>
                        <td>{{ $tshirtImage->name }}</td>
                        <td>{{ $tshirtImage->description }}</td>
                        <td>{{ $tshirtImage->image_url }}</td>
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

@endsection