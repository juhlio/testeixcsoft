<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'recipient_document' => ['required', 'string', 'exists:users,document'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $senderDocument = $request->user()->document; // Usando o documento do usuário autenticado
        $recipientDocument = $validated['recipient_document'];
        $amount = $validated['amount'];

        // Recuperar o usuário remetente e destinatário
        $sender = User::where('document', $senderDocument)->first();
        $recipient = User::where('document', $recipientDocument)->first();

        // Verificar se os usuários existem
        if (!$sender) {
            return redirect()->back()->withErrors(['sender' => 'Remetente não encontrado.']);
        }

        if (!$recipient) {
            return redirect()->back()->withErrors(['recipient' => 'Destinatário não encontrado.']);
        }

        // Verificar se o remetente tem saldo suficiente
        if ($sender->wallet->balance < $amount) {
            return redirect()->back()->withErrors(['amount' => 'Saldo insuficiente.']);
        }

        // Consultar serviço externo autorizador (mock)
        $authorization = $this->consultExternalService($amount, $senderDocument, $recipientDocument);

        if (!$authorization) {
            return redirect()->back()->withErrors(['amount' => 'Autorização da transferência falhou.']);
        }

        // Criar a transação
        $transaction = DB::transaction(function () use ($sender, $recipient, $amount) {
            // Atualizar o saldo dos usuários
            $sender->wallet->balance -= $amount;
            $sender->wallet->save();

            $recipient->wallet->balance += $amount;
            $recipient->wallet->save();

            // Criar a transação
            return Transaction::create([
                'sender_document' => $sender->document,
                'recipient_document' => $recipient->document,
                'amount' => $amount,
            ]);
        });

        // Enviar notificações (mock)
        $this->sendNotification($sender, $recipient, $amount);

        return redirect()->back()->with('status', 'Transferência realizada com sucesso! ID da transação: ' . $transaction->id);
    }

    /**
     * Simula a consulta a um serviço externo para autorização da transferência.
     *
     * @param  float  $amount
     * @param  string  $senderDocument
     * @param  string  $recipientDocument
     * @return bool
     */
    private function consultExternalService($amount, $senderDocument, $recipientDocument)
    {
        // Simulação de autorização de serviço externo
        return true; // Retorne true para simular sucesso
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
