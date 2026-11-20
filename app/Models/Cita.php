<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Cita médica entre un paciente y un médico.
 */
class Cita extends Model
{
    public const ESTADOS = ['programada', 'atendida', 'cancelada'];

    protected $fillable = [
        'paciente_id', 'medico_id', 'fecha_hora', 'motivo', 'estado', 'observaciones',
    ];

    protected function casts(): array
    {
        return ['fecha_hora' => 'datetime'];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class)->withTrashed();
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class)->withTrashed();
    }
}
