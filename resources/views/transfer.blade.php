@extends('adminlte::page')

@section('title', 'Transferir Dinheiro')

@section('content_header')
    <h1>Transferir Dinheiro</h1>
@stop

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('transfer') }}" method="POST">
            @csrf

            <input type="hidden" name="sender_document" value="{{ auth()->user()->document }}">

            <div class="form-group">
                <label for="recipient_document">Documento do Destinatário</label>
                <input type="text" name="recipient_document" id="recipient_document" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="amount">Valor</label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
            </div>

            <button type="submit" class="btn btn-primary">Transferir</button>
        </form>
    </div>
@stop
