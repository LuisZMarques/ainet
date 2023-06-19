@extends('template.layout')

@section('titulo', 'Perfil de Cliente')

@section('main')
    <div>
        @include('customers.shared.fields', ['readonlyData' => true])
    </div>
    <div class="d-flex justify-content-between">
        <div class="d-flex">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
        <div class="d-flex">
            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">Editar</a>
            <form method="POST" action="{{ route('customers.destroy', $customer) }}" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Eliminar</button>
            </form>
        </div>
    </div>
@endsection
