@extends('template.layout')

@section('titulo', 'Encomendas do Cliente ' . Auth::user()->name)

@section('main')
    <div class="container">
        <table class="table table-dark table-bordered text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach ($orders as $order)
                    
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->date }}</td>
                        <td>{{ $order->total_price }}</td>
                        <td>
                            <a href="/encomendas/{{ $order->id }}/edit" class="btn btn-primary">Alterar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
