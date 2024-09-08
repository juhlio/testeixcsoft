<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id(); // Isso cria uma coluna `id` do tipo BIGINT com auto incremento
            $table->unsignedBigInteger('user_id'); // Tipo de dado deve ser o mesmo que o tipo de `id` em `users`
            $table->decimal('balance', 15, 2); // Saldo com duas casas decimais
            $table->timestamps();

            // Define a chave estrangeira
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}
