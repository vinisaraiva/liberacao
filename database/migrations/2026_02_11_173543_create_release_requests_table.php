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
        Schema::create('release_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->string('created_by_name_snapshot', 120);
            $table->string('sector', 40);
            $table->string('motivo', 120)->nullable();
            $table->string('observacao', 255)->nullable();
            $table->dateTime('valid_until');
            $table->string('status', 20);
            $table->dateTime('confirmed_at')->nullable()->index();
            $table->foreignId('confirmed_terminal_id')->nullable()->constrained('terminals');
            $table->string('confirmed_terminal_name_snapshot', 120)->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['student_id', 'status']);
            $table->index(['created_by_user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('release_requests');
    }
};
