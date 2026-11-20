<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Especialidad;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Database\Seeder;

/**
 * Datos de demostración para la sustentación (ficticios).
 */
class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        $especialidades = [
            'Medicina general' => 'Atención primaria y control general de salud',
            'Pediatría' => 'Atención médica de niños y adolescentes',
            'Ginecología' => 'Salud de la mujer y control prenatal',
            'Cardiología' => 'Diagnóstico y tratamiento de enfermedades del corazón',
            'Ortopedia' => 'Lesiones del sistema musculoesquelético',
            'Odontología' => 'Salud oral',
        ];

        $ids = [];
        foreach ($especialidades as $nombre => $descripcion) {
            $ids[$nombre] = Especialidad::firstOrCreate(
                ['nombre' => $nombre],
                ['descripcion' => $descripcion]
            )->id;
        }

        $medicos = [
            ['1001001001', 'Laura', 'Gómez Pérez', 'RM-1001', 'Medicina general'],
            ['1001001002', 'Carlos', 'Rodríguez Silva', 'RM-1002', 'Pediatría'],
            ['1001001003', 'Marta', 'Herrera Díaz', 'RM-1003', 'Ginecología'],
            ['1001001004', 'Andrés', 'Pineda Rojas', 'RM-1004', 'Cardiología'],
            ['1001001005', 'Sofía', 'Medina Castro', 'RM-1005', 'Odontología'],
        ];

        $idsMedicos = [];
        foreach ($medicos as [$documento, $nombres, $apellidos, $registro, $especialidad]) {
            $idsMedicos[] = Medico::firstOrCreate(
                ['documento' => $documento],
                [
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'registro_medico' => $registro,
                    'especialidad_id' => $ids[$especialidad],
                    'telefono' => '3100000' . substr($documento, -3),
                    'email' => strtolower($nombres) . '@hospital.test',
                    'activo' => true,
                ]
            )->id;
        }

        $pacientes = [
            ['2000000001', 'Juan Camilo', 'Torres Vargas', '1988-03-14', 'M', 'Sura', 'O+'],
            ['2000000002', 'María Fernanda', 'López Ruiz', '1992-07-02', 'F', 'Sanitas', 'A+'],
            ['2000000003', 'Pedro Antonio', 'Martínez Gil', '1975-11-23', 'M', 'Nueva EPS', 'B+'],
            ['2000000004', 'Ana Lucía', 'Ramírez Soto', '2015-05-30', 'F', 'Sura', 'O-'],
            ['2000000005', 'Luis Eduardo', 'Castaño Mora', '1960-01-09', 'M', 'Sanitas', 'AB+'],
            ['2000000006', 'Valentina', 'Ortiz Peña', '1999-09-17', 'F', 'Nueva EPS', 'A-'],
            ['2000000007', 'Diego Alejandro', 'Cárdenas Ríos', '2003-12-05', 'M', 'Sura', 'B-'],
            ['2000000008', 'Camila', 'Suárez Prieto', '1983-04-27', 'F', 'Compensar', 'O+'],
        ];

        $idsPacientes = [];
        foreach ($pacientes as [$documento, $nombres, $apellidos, $nacimiento, $sexo, $eps, $sangre]) {
            $idsPacientes[] = Paciente::firstOrCreate(
                ['documento' => $documento],
                [
                    'tipo_documento' => 'CC',
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'fecha_nacimiento' => $nacimiento,
                    'sexo' => $sexo,
                    'telefono' => '3200000' . substr($documento, -3),
                    'direccion' => 'Puerto Boyacá, Boyacá',
                    'eps' => $eps,
                    'grupo_sanguineo' => $sangre,
                ]
            )->id;
        }

        if (Cita::count() === 0) {
            $motivos = ['Control general', 'Dolor de cabeza persistente', 'Control de crecimiento', 'Chequeo cardiovascular', 'Limpieza dental', 'Control prenatal'];

            foreach ($motivos as $i => $motivo) {
                Cita::create([
                    'paciente_id' => $idsPacientes[$i],
                    'medico_id' => $idsMedicos[$i % count($idsMedicos)],
                    'fecha_hora' => now()->addDays($i + 1)->setTime(8 + $i, 0),
                    'motivo' => $motivo,
                    'estado' => 'programada',
                ]);
            }
        }

        if (Consulta::count() === 0) {
            Consulta::create([
                'paciente_id' => $idsPacientes[0],
                'medico_id' => $idsMedicos[0],
                'fecha_consulta' => now()->subDays(7),
                'motivo' => 'Fiebre y malestar general',
                'diagnostico' => 'Infección viral de vías respiratorias altas',
                'tratamiento' => 'Reposo, hidratación y acetaminofén según indicación médica',
            ]);
        }
    }
}
