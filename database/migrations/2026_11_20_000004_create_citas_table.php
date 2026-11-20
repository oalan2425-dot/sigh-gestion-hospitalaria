<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla de citas médicas (paciente + médico + fecha/hora).
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->restrictOnDelete();
            $table->foreignId('medico_id')->constrained('medicos')->restrictOnDelete();
            $table->dateTime('fecha_hora');
            $table->string('motivo', 200);
            $table->string('estado', 20)->default('programada');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['medico_id', 'fecha_hora']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
