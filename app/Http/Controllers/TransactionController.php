<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    /**
     * Exibe o formulário de transferência.
     *
     * @return \Illuminate\View\View
     */
    public function showTransferForm()
    {
        return view('transfer');
    }

    /**
     * Processa a transferência de dinheiro.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function transfer(Request $request)
    {
        // Validação dos dados da solicitação
        $validated = $request->validate([
            'recipient_document' => ['required', 'string', 'exists:users,documento'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $senderDocument = $request->user()->documento; // Usando o documento do usuário autenticado
        $recipientDocument = $validated['recipient_document'];
        $amount = $validated['amount'];

        // Recuperar o usuário remetente e destinatário
        $sender = User::where('documento', $senderDocument)->first();
        $recipient = User::where('documento', $recipientDocument)->first();

        // Verificar se o remetente tem saldo suficiente
        if ($sender->wallet->balance < $amount) {
            return redirect()->back()->withErrors(['amount' => 'Saldo insuficiente.']);
        }

        // Criar a transação com status 'pending'
        $transaction = Transactions::create([
            'sender_document' => $sender->documento,
            'recipient_document' => $recipient->documento,
            'amount' => $amount,
            'status' => 'pending', // Definindo o status como 'pending'
        ]);

        // Consultar serviço externo autorizador
        $authorization = $this->consultExternalService($transaction->id);

        if ($authorization) {
            // Se autorizado, confirmar a transação e alterar status para 'completed'
            DB::transaction(function () use ($sender, $recipient, $amount, $transaction) {
                // Atualizar o saldo dos usuários
                $sender->wallet->balance -= $amount;
                $sender->wallet->save();

                $recipient->wallet->balance += $amount;
                $recipient->wallet->save();

                // Atualizar o status da transação
                $transaction->status = 'completed';
                $transaction->save();
            });

            return redirect()->back()->with('status', 'Transferência realizada com sucesso! ID da transação: ' . $transaction->id);
        } else {
            // Se não autorizado, alterar o status para 'failed'
            $transaction->status = 'failed';
            $transaction->save();

            return redirect()->back()->withErrors(['amount' => 'Autorização da transferência falhou.']);
        }
    }

    /**
     * Simula a consulta a um serviço externo para autorização da transferência.
     *
     * @param  int  $transactionId
     * @return bool
     */
    private function consultExternalService($transactionId)
{
    // URL do serviço externo
    $url = 'https://66ad1f3cb18f3614e3b478f5.mockapi.io/v1/auth';

    // Realiza a requisição HTTP GET para o serviço externo
    $response = Http::get($url);

    // Verifica se a resposta foi bem-sucedida
    if ($response->successful()) {
        $responseData = $response->json();

        // Verifica se a mensagem de autorização está presente
        $authorized = false;
        foreach ($responseData as $item) {
            if (isset($item['message']) && $item['message'] === 'Autorizado') {
                $authorized = true;
                break;
            }
        }

        // Verifica se o ID da transação está na lista de IDs autorizados
        if ($authorized) {
            $authorizedIds = array_column($responseData, 'id');
            return in_array($transactionId, $authorizedIds);
        }
    }

    return false;
}

    /**
     * Simula o envio de notificações para o remetente e destinatário.
     *
     * @param  \App\Models\User  $sender
     * @param  \App\Models\User  $recipient
     * @param  float  $amount
     * @return void
     */
    private function sendNotification($sender, $recipient, $amount)
    {
        // Simulação de envio de notificações
        // Você pode implementar a lógica para enviar e-mails ou SMS aqui
    }
}
