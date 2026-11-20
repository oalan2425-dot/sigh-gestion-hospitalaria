<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Especialidad médica (Medicina general, Pediatría, etc.).
 */
class Especialidad extends Model
{
    /** El plural en español no sigue la regla en inglés de Laravel. */
    protected $table = 'especialidades';

    protected $fillable = ['nombre', 'descripcion'];

    public function medicos(): HasMany
    {
        return $this->hasMany(Medico::class);
    }
}
