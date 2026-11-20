<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Paciente registrado en el hospital.
 */
class Paciente extends Model
{
    use SoftDeletes;

    public const TIPOS_DOCUMENTO = [
        'CC' => 'Cédula de ciudadanía',
        'TI' => 'Tarjeta de identidad',
        'RC' => 'Registro civil',
        'CE' => 'Cédula de extranjería',
        'PA' => 'Pasaporte',
    ];

    public const SEXOS = ['F' => 'Femenino', 'M' => 'Masculino', 'O' => 'Otro'];

    public const GRUPOS_SANGUINEOS = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    protected $fillable = [
        'tipo_documento', 'documento', 'nombres', 'apellidos', 'fecha_nacimiento',
        'sexo', 'telefono', 'email', 'direccion', 'eps', 'grupo_sanguineo',
    ];

    protected function casts(): array
    {
        return ['fecha_nacimiento' => 'date'];
    }

    /** Nombre completo: "Apellidos Nombres". */
    protected function nombreCompleto(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->apellidos . ' ' . $this->nombres,
        );
    }

    /** Edad en años cumplidos. */
    protected function edad(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fecha_nacimiento?->age,
        );
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
