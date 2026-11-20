<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\View\View;

/**
 * Panel principal con indicadores del hospital.
 */
class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $indicadores = [
            'pacientes' => Paciente::count(),
            'medicos' => Medico::where('activo', true)->count(),
            'citas_hoy' => Cita::whereDate('fecha_hora', today())
                ->where('estado', 'programada')->count(),
            'citas_pendientes' => Cita::where('estado', 'programada')
                ->where('fecha_hora', '>=', now())->count(),
        ];

        $proximasCitas = Cita::with(['paciente', 'medico.especialidad'])
            ->where('estado', 'programada')
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora')
            ->limit(5)
            ->get();

        return view('dashboard', compact('indicadores', 'proximasCitas'));
    }
}
