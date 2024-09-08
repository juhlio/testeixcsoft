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

        <!-- Exibe todos os erros de validação de uma vez -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transfer') }}" method="POST">
            @csrf

            <input type="hidden" name="sender_document" value="{{ auth()->user()->document }}">

            <div class="form-group">
                <label for="recipient_document">Documento do Destinatário</label>
                <input type="text" name="recipient_document" id="recipient_document" class="form-control @error('recipient_document') is-invalid @enderror" required>
                <!-- Exibe o erro específico deste campo -->
                @error('recipient_document')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="amount">Valor</label>
                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" step="0.01" required>
                <!-- Exibe o erro específico deste campo -->
                @error('amount')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Transferir</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar à Home</a>
            </div>
        </form>
    </div>
@stop
