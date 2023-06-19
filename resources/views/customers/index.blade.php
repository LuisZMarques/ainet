@extends('template.layout')

@section('titulo', 'Todos os Clientes')

@section('main')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
        <form action="{{ route('customers.index') }}" method="GET" class="search-bar mb-3 d-flex">
                <input type="text" id="search-input" placeholder="Procura por Nome" class="form-control" name="search">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
            <div class="table-responsive">
                <table class="table table-dark table-striped" style="background-color: #f1f1f1;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Nif</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->user->name }}</td>
                            <td>{{ $customer->user->email }}</td>
                            <td>{{ $customer->nif }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-secondary btn-sm "><i class="fas fa-pencil-alt"></i></a>
                                    <form method="POST" action="{{ route('customers.destroy', $customer->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Também vai eliminar todas as encomendas, imagens relacionadas com este Cliente! Tem a certeza? ')"><i class='fas fa-trash'></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $customers->links() }}
        </div>
    </div>
@endsection