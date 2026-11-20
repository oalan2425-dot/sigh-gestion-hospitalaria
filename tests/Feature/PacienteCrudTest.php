<?php

namespace Tests\Feature;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PacienteCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, string> */
    private function datosPaciente(array $cambios = []): array
    {
        return array_merge([
            'tipo_documento' => 'CC',
            'documento' => '123456789',
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'fecha_nacimiento' => '1990-05-20',
            'sexo' => 'M',
            'telefono' => '3001234567',
            'eps' => 'Sura',
        ], $cambios);
    }

    public function test_se_puede_registrar_un_paciente(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/pacientes', $this->datosPaciente())
            ->assertRedirect(route('pacientes.index'));

        $this->assertDatabaseHas('pacientes', ['documento' => '123456789', 'nombres' => 'Juan']);
    }

    public function test_el_documento_no_se_puede_repetir(): void
    {
        Paciente::create($this->datosPaciente());

        $this->actingAs(User::factory()->create())
            ->post('/pacientes', $this->datosPaciente(['nombres' => 'Otro']))
            ->assertSessionHasErrors('documento');

        $this->assertDatabaseCount('pacientes', 1);
    }

    public function test_los_campos_obligatorios_se_validan(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/pacientes', [])
            ->assertSessionHasErrors(['documento', 'nombres', 'apellidos', 'fecha_nacimiento', 'sexo']);
    }

    public function test_se_puede_listar_y_buscar_pacientes(): void
    {
        Paciente::create($this->datosPaciente());
        Paciente::create($this->datosPaciente(['documento' => '987654321', 'nombres' => 'María', 'apellidos' => 'Gómez']));

        $this->actingAs(User::factory()->create())
            ->get('/pacientes?buscar=Gómez')
            ->assertOk()
            ->assertSee('Gómez')
            ->assertDontSee('Pérez');
    }

    public function test_se_puede_actualizar_un_paciente(): void
    {
        $paciente = Paciente::create($this->datosPaciente());

        $this->actingAs(User::factory()->create())
            ->put("/pacientes/{$paciente->id}", $this->datosPaciente(['nombres' => 'Juan Carlos']))
            ->assertRedirect(route('pacientes.index'));

        $this->assertDatabaseHas('pacientes', ['id' => $paciente->id, 'nombres' => 'Juan Carlos']);
    }

    public function test_eliminar_un_paciente_es_una_eliminacion_logica(): void
    {
        $paciente = Paciente::create($this->datosPaciente());

        $this->actingAs(User::factory()->create())
            ->delete("/pacientes/{$paciente->id}")
            ->assertRedirect(route('pacientes.index'));

        $this->assertSoftDeleted('pacientes', ['id' => $paciente->id]);
    }
}
