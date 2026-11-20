<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Consulta médica realizada (historia clínica básica).
 */
class Consulta extends Model
{
    protected $fillable = [
        'paciente_id', 'medico_id', 'fecha_consulta', 'motivo', 'diagnostico', 'tratamiento',
    ];

    protected function casts(): array
    {
        return ['fecha_consulta' => 'datetime'];
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
