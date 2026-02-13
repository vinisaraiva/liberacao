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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('matricula', 32)->unique();
            $table->string('nome', 120);
            $table->string('nome_busca', 120)->index();
            $table->foreignId('curso_id')->constrained('courses');
            $table->foreignId('serie_id')->constrained('series');
            $table->foreignId('turma_id')->constrained('turmas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
