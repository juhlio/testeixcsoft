<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sender;
    protected $recipient;
    protected $amount;

    public function __construct($sender, $recipient, $amount)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->amount = $amount;
    }

    public function handle()
    {
        $this->sendNotifications();
    }

    /**
     * Envia as notificações para o remetente e o destinatário.
     *
     * @return void
     */
    protected function sendNotifications()
    {
        // Dados para notificação do remetente
        $senderNotificationData = [
            'sender' => $this->sender->name,
            'recipient' => $this->recipient->name,
            'amount' => $this->amount,
            'message' => "Você transferiu R$ {$this->amount} para {$this->recipient->name}.",
        ];

        // Dados para notificação do destinatário
        $recipientNotificationData = [
            'sender' => $this->sender->name,
            'recipient' => $this->recipient->name,
            'amount' => $this->amount,
            'message' => "Você recebeu R$ {$this->amount} de {$this->sender->name}.",
        ];

        // URL do serviço de notificação
        $url = 'https://66ad1f3cb18f3614e3b478f5.mockapi.io/v1/send';

        // Enviar notificação para o remetente
        $this->sendNotification($url, $senderNotificationData, 'Remetente');

        // Enviar notificação para o destinatário
        $this->sendNotification($url, $recipientNotificationData, 'Destinatário');
    }

    /**
     * Envia uma notificação para a API.
     *
     * @param string $url
     * @param array $notificationData
     * @param string $recipientType
     * @return void
     */
    protected function sendNotification($url, $notificationData, $recipientType)
    {
        $response = Http::post($url, $notificationData);

        if ($response->successful()) {
            $responseData = $response->json();

            Log::info("Resposta da API ({$recipientType})", ['response' => $responseData]);

            if (isset($responseData[0]) && isset($responseData[0]['message']) && $responseData[0]['message'] === 'Notificação enviada') {
                Log::info("Notificação enviada com sucesso para o {$recipientType}.");
            } else {
                Log::error("Falha na notificação para o {$recipientType}. Estrutura da resposta incorreta.");
            }
        } else {
            Log::error("Falha ao enviar notificação para o {$recipientType}. Status:", ['status' => $response->status(), 'response' => $response->body()]);
        }
    }
}
