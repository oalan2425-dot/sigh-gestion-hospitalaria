<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * CRUD de consultas médicas (historia clínica básica).
 */
class ConsultaController extends Controller
{
    public function index(): View
    {
        $consultas = Consulta::with(['paciente', 'medico'])
            ->orderByDesc('fecha_consulta')
            ->paginate(10);

        return view('consultas.index', compact('consultas'));
    }

    public function create(Request $request): View
    {
        $consulta = new Consulta([
            'paciente_id' => $request->query('paciente_id'),
            'fecha_consulta' => now(),
        ]);

        return view('consultas.form', $this->datosFormulario($consulta));
    }

    public function store(Request $request): RedirectResponse
    {
        Consulta::create($request->validate($this->reglas()));

        return redirect()->route('consultas.index')
            ->with('exito', 'Consulta registrada correctamente.');
    }

    public function edit(Consulta $consulta): View
    {
        return view('consultas.form', $this->datosFormulario($consulta));
    }

    public function update(Request $request, Consulta $consulta): RedirectResponse
    {
        $consulta->update($request->validate($this->reglas()));

        return redirect()->route('consultas.index')
            ->with('exito', 'Consulta actualizada correctamente.');
    }

    public function destroy(Consulta $consulta): RedirectResponse
    {
        $consulta->delete();

        return redirect()->route('consultas.index')
            ->with('exito', 'Consulta eliminada correctamente.');
    }

    /**
     * @return array<string, mixed>
     */
    private function datosFormulario(Consulta $consulta): array
    {
        return [
            'consulta' => $consulta,
            'pacientes' => Paciente::orderBy('apellidos')->orderBy('nombres')->get(),
            'medicos' => Medico::where('activo', true)->orderBy('apellidos')->get(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function reglas(): array
    {
        return [
            'paciente_id' => ['required', Rule::exists('pacientes', 'id')],
            'medico_id' => ['required', Rule::exists('medicos', 'id')],
            'fecha_consulta' => ['required', 'date'],
            'motivo' => ['required', 'string', 'max:200'],
            'diagnostico' => ['required', 'string', 'max:2000'],
            'tratamiento' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
