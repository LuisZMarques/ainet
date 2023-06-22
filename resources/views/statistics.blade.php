@extends('template.layout')

@section('titulo', 'Estatisticas')

@section('main')

<div class="container">
    <h1>Statistics</h1>

    <div class="row">
        <div class="col-md-6">
            {!! $earningsChart->container() !!}
        </div>
        <div class="col-md-6">
            {!! $orderCountChart->container() !!}
        </div>
    </div>
</div>

{{ $earningsChart->script() }}
{{ $orderCountChart->script() }}

@endsection