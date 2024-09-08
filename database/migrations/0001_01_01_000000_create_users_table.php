<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cria a tabela 'users'
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Cria a tabela 'password_reset_tokens'
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Cria a tabela 'sessions'
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Adiciona as colunas 'documento' e 'tipo' à tabela 'users'
        Schema::table('users', function (Blueprint $table) {
            $table->string('documento')->nullable()->after('email'); // Adiciona a coluna 'documento' após 'email'
            $table->enum('tipo', ['fisica', 'juridica'])->default('fisica')->after('documento'); // Adiciona a coluna 'tipo' após 'documento'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove as colunas 'documento' e 'tipo' da tabela 'users'
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['documento', 'tipo']);
        });

        // Remove as tabelas criadas
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
