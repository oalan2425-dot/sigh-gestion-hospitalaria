<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * CRUD de citas médicas. Un médico no puede tener dos citas activas a la misma hora.
 */
class CitaController extends Controller
{
    public function index(Request $request): View
    {
        $estado = $request->query('estado');

        $citas = Cita::with(['paciente', 'medico.especialidad'])
            ->when(in_array($estado, Cita::ESTADOS, true), fn ($q) => $q->where('estado', $estado))
            ->orderByDesc('fecha_hora')
            ->paginate(10)
            ->withQueryString();

        return view('citas.index', compact('citas', 'estado'));
    }

    public function create(): View
    {
        return view('citas.form', $this->datosFormulario(new Cita(['estado' => 'programada'])));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglas(true));

        if ($this->existeCruce($datos)) {
            return back()->withInput()->withErrors([
                'fecha_hora' => 'El médico ya tiene una cita programada en esa fecha y hora.',
            ]);
        }

        Cita::create($datos);

        return redirect()->route('citas.index')
            ->with('exito', 'Cita programada correctamente.');
    }

    public function edit(Cita $cita): View
    {
        return view('citas.form', $this->datosFormulario($cita));
    }

    public function update(Request $request, Cita $cita): RedirectResponse
    {
        $datos = $request->validate($this->reglas(false));

        if ($this->existeCruce($datos, $cita)) {
            return back()->withInput()->withErrors([
                'fecha_hora' => 'El médico ya tiene una cita programada en esa fecha y hora.',
            ]);
        }

        $cita->update($datos);

        return redirect()->route('citas.index')
            ->with('exito', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita): RedirectResponse
    {
        $cita->delete();

        return redirect()->route('citas.index')
            ->with('exito', 'Cita eliminada correctamente.');
    }

    /**
     * Datos que necesita el formulario (listas de pacientes y médicos).
     *
     * @return array<string, mixed>
     */
    private function datosFormulario(Cita $cita): array
    {
        return [
            'cita' => $cita,
            'pacientes' => Paciente::orderBy('apellidos')->orderBy('nombres')->get(),
            'medicos' => Medico::with('especialidad')->where('activo', true)->orderBy('apellidos')->get(),
        ];
    }

    /**
     * Indica si el médico ya tiene otra cita no cancelada en la misma fecha y hora.
     *
     * @param  array<string, mixed>  $datos
     */
    private function existeCruce(array $datos, ?Cita $cita = null): bool
    {
        if ($datos['estado'] === 'cancelada') {
            return false;
        }

        return Cita::query()
            ->where('medico_id', $datos['medico_id'])
            ->where('fecha_hora', Carbon::parse($datos['fecha_hora']))
            ->where('estado', '!=', 'cancelada')
            ->when($cita, fn ($q) => $q->where('id', '!=', $cita->id))
            ->exists();
    }

    /**
     * @return array<string, mixed>
     */
    private function reglas(bool $esNueva): array
    {
        return [
            'paciente_id' => ['required', Rule::exists('pacientes', 'id')],
            'medico_id' => ['required', Rule::exists('medicos', 'id')],
            'fecha_hora' => $esNueva ? ['required', 'date', 'after:now'] : ['required', 'date'],
            'motivo' => ['required', 'string', 'max:200'],
            'estado' => ['required', Rule::in(Cita::ESTADOS)],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
