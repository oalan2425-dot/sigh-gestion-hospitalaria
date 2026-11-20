<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Médico del hospital.
 */
class Medico extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'documento', 'nombres', 'apellidos', 'registro_medico',
        'especialidad_id', 'telefono', 'email', 'activo',
    ];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    /** Nombre completo: "Apellidos Nombres". */
    protected function nombreCompleto(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->apellidos . ' ' . $this->nombres,
        );
    }

    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }
}
