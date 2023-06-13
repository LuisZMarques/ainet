@extends('template.layout')

@section('titulo', 'Lista de Encomendas')

@section('main')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8"> 
            <form action="{{ route('orders.index') }}" method="GET" class="search-bar mb-3 d-flex">
                <select id="status-select" class="form-control" style="width:10%" name="status">
                    <option value="">Todos os Estados</option>
                    <option value="pending">Pendente</option>
                    <option value="closed">Fechada</option>
                    <option value="canceled">Anulada</option>
                </select>
                <input type="text" id="search-input" placeholder="Procura por Nome de Cliente" class="form-control" name="search">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
            <div class="table-responsive">
                <table class="table table-dark table-striped" style="background-color: #f1f1f1;">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Preço Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->customer->user->name }}</td>
                                <td>{{ $order->date }}</td>
                                <td>{{ $order->total_price }}</td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}">Ver</a>
                                    <a href="{{ route('orders.edit', $order->id) }}">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
@endsection
