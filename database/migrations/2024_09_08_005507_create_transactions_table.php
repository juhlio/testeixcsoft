<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('sender_document'); // Documento do remetente
            $table->string('recipient_document'); // Documento do destinatário
            $table->decimal('amount', 10, 2); // Valor da transação com 2 casas decimais
            $table->string('status')->default('pending'); // Status da transação com valor padrão 'pending'
            $table->timestamps(); // timestamps para created_at e updated_at

            // Índices para buscar mais rapidamente
            $table->index('sender_document');
            $table->index('recipient_document');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
