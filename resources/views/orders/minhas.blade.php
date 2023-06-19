@extends('template.layout')

@section('titulo', 'Encomendas do Cliente ' . Auth::user()->name)

@section('main')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8"> 
            <form action="{{ route('orders.minhas') }}" method="GET" class="search-bar mb-3 d-flex">
                <select id="status-select" class="form-control" style="width:20%" name="status">
                    <option value="">Todos os Estados</option>
                    <option value="pending">Pendente</option>
                    <option value="paid">Paga</option>
                    <option value="closed">Fechada</option>
                    <option value="canceled">Anulada</option>
                </select>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
            <div class="table-responsive">
                <table class="table table-dark table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->date }}</td>
                                <td>{{ $order->total_price }}</td>
                                <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
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
