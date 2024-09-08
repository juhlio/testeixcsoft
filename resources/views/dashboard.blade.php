@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="text-center">Dashboard</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Saldo Atual</div>
                <div class="card-body">
                    @if($balance)
                        <p>Saldo: R$ {{ number_format($balance->balance, 2, ',', '.') }}</p>
                    @else
                        <p>Saldo não disponível.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Transações Enviadas</div>
                <div class="card-body">
                    @if($moneySent->isEmpty())
                        <p>Você não enviou nenhuma transação.</p>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th>Destinatário</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($moneySent as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                                        <td>{{ $transaction->recipient_document }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Transações Recebidas</div>
                <div class="card-body">
                    @if($moneyReceived->isEmpty())
                        <p>Você não recebeu nenhuma transação.</p>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th>Remetente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($moneyReceived as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                                        <td>{{ $transaction->sender_document }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-center">
            <a href="{{ route('transfer') }}" class="btn btn-primary">Fazer Transferência</a>
        </div>
    </div>
</div>
@endsection
