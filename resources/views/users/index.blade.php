@extends('template.layout')

@section('titulo', 'Todos os Utilizadores')

@section('main')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <a href="{{ route('users.create') }}" class="btn btn-primary mb-2">Criar Utilizador</a>
            <form action="{{ route('users.index') }}" method="GET" class="search-bar mb-3 d-flex">
                <input type="text" id="search-input" placeholder="Procura por Nome" class="form-control" name="search">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
            <div class="table-responsive">
                <table class="table table-dark table-striped" style="background-color: #f1f1f1;">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td> {{ $user->user_type }} </td>
                            <td>    @if ($user->blocked == 0)
                                        Desbloqueado
                                    @else
                                        Bloqueado
                                    @endif 
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary btn-sm "><i class="fas fa-pencil-alt"></i></a>
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem a certeza?')"><i class='fas fa-trash'></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>
@endsection