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
        Schema::create('autorizacoes_chave', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('laboratorio_id')->constrained('laboratorios');
            $table->foreignId('autorizado_por_user_id')->constrained('users');
            $table->string('autorizado_por_nome_snapshot', 120);
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_until');
            $table->string('observacao', 255)->nullable();
            $table->string('status', 20); // ATIVA, CANCELADA, EXPIRADA
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['laboratorio_id', 'status']);
            $table->index(['autorizado_por_user_id', 'created_at']);
            $table->index('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autorizacoes_chave');
    }
};
