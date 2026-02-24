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
        Schema::create('movimentacoes_chave', function (Blueprint $table) {
            $table->id();
            $table->foreignId('autorizacao_id')->constrained('autorizacoes_chave');
            $table->string('acao', 20); // RETIRADA, DEVOLUCAO
            $table->foreignId('terminal_id')->nullable()->constrained('terminals');
            $table->string('terminal_name_snapshot', 120)->nullable();
            $table->dateTime('realizado_em');
            $table->string('observacao', 255)->nullable();

            $table->index(['autorizacao_id', 'realizado_em']);
            $table->index(['acao', 'realizado_em']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes_chave');
    }
};
