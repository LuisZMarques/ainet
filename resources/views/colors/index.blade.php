@extends('template.layout')

@section('titulo', 'Lista de Cores')

@section('main')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <a href="{{ route('colors.create') }}" class="btn btn-primary mb-3">Nova Cor</a>
            <div class="table-responsive">
                <table class="table table-dark table-striped" style="background-color: #f1f1f1;">
                    <thead>
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($colors as $color)
                            <tr>
                                <td>{{ $color->code }}</td>
                                <td>{{ $color->name }}</td>
                                <td>
                                    <a href="{{ route('colors.show', $color->code) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('colors.edit', $color->code) }}" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                    <form method="POST" action="{{ route('colors.destroy', $color) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta cor?')"><i class='fas fa-trash'></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $colors->links() }}
        </div>
    </div>
@endsection
