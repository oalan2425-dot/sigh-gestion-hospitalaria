<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\Especialidad;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitaTest extends TestCase
{
    use RefreshDatabase;

    private function crearMedico(): Medico
    {
        $especialidad = Especialidad::create(['nombre' => 'Medicina general']);

        return Medico::create([
            'documento' => '111',
            'nombres' => 'Laura',
            'apellidos' => 'Gómez',
            'registro_medico' => 'RM-1',
            'especialidad_id' => $especialidad->id,
            'activo' => true,
        ]);
    }

    private function crearPaciente(string $documento = '222'): Paciente
    {
        return Paciente::create([
            'tipo_documento' => 'CC',
            'documento' => $documento,
            'nombres' => 'Pedro',
            'apellidos' => 'Ruiz',
            'fecha_nacimiento' => '1985-01-01',
            'sexo' => 'M',
        ]);
    }

    public function test_se_puede_programar_una_cita(): void
    {
        $medico = $this->crearMedico();
        $paciente = $this->crearPaciente();

        $this->actingAs(User::factory()->create())
            ->post('/citas', [
                'paciente_id' => $paciente->id,
                'medico_id' => $medico->id,
                'fecha_hora' => now()->addDays(2)->setTime(9, 0)->format('Y-m-d\TH:i'),
                'motivo' => 'Control general',
                'estado' => 'programada',
            ])
            ->assertRedirect(route('citas.index'));

        $this->assertDatabaseCount('citas', 1);
    }

    public function test_un_medico_no_puede_tener_dos_citas_a_la_misma_hora(): void
    {
        $medico = $this->crearMedico();
        $paciente = $this->crearPaciente();
        $otro = $this->crearPaciente('333');
        $fecha = now()->addDays(2)->setTime(10, 0);

        Cita::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'fecha_hora' => $fecha,
            'motivo' => 'Primera cita',
            'estado' => 'programada',
        ]);

        $this->actingAs(User::factory()->create())
            ->post('/citas', [
                'paciente_id' => $otro->id,
                'medico_id' => $medico->id,
                'fecha_hora' => $fecha->format('Y-m-d\TH:i'),
                'motivo' => 'Segunda cita',
                'estado' => 'programada',
            ])
            ->assertSessionHasErrors('fecha_hora');

        $this->assertDatabaseCount('citas', 1);
    }

    public function test_no_se_puede_programar_una_cita_en_el_pasado(): void
    {
        $medico = $this->crearMedico();
        $paciente = $this->crearPaciente();

        $this->actingAs(User::factory()->create())
            ->post('/citas', [
                'paciente_id' => $paciente->id,
                'medico_id' => $medico->id,
                'fecha_hora' => now()->subDay()->format('Y-m-d\TH:i'),
                'motivo' => 'Control',
                'estado' => 'programada',
            ])
            ->assertSessionHasErrors('fecha_hora');
    }

    public function test_no_se_elimina_una_especialidad_con_medicos(): void
    {
        $medico = $this->crearMedico();

        $this->actingAs(User::factory()->create())
            ->delete("/especialidades/{$medico->especialidad_id}")
            ->assertSessionHas('error');

        $this->assertDatabaseHas('especialidades', ['id' => $medico->especialidad_id]);
    }
}
