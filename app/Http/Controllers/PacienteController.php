<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * CRUD de pacientes.
 */
class PacienteController extends Controller
{
    /** Lista pacientes con búsqueda por nombre, apellido o documento. */
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));

        $pacientes = Paciente::query()
            ->when($buscar !== '', function ($consulta) use ($buscar) {
                $consulta->where(function ($q) use ($buscar) {
                    $q->where('nombres', 'like', "%{$buscar}%")
                        ->orWhere('apellidos', 'like', "%{$buscar}%")
                        ->orWhere('documento', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->paginate(10)
            ->withQueryString();

        return view('pacientes.index', compact('pacientes', 'buscar'));
    }

    public function create(): View
    {
        return view('pacientes.form', ['paciente' => new Paciente()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglas());

        Paciente::create($datos);

        return redirect()->route('pacientes.index')
            ->with('exito', 'Paciente registrado correctamente.');
    }

    /** Muestra la ficha del paciente con sus citas y consultas. */
    public function show(Paciente $paciente): View
    {
        $paciente->load([
            'citas' => fn ($q) => $q->with('medico')->orderByDesc('fecha_hora'),
            'consultas' => fn ($q) => $q->with('medico')->orderByDesc('fecha_consulta'),
        ]);

        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente): View
    {
        return view('pacientes.form', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente): RedirectResponse
    {
        $datos = $request->validate($this->reglas($paciente));

        $paciente->update($datos);

        return redirect()->route('pacientes.index')
            ->with('exito', 'Paciente actualizado correctamente.');
    }

    /** Elimina de forma lógica (SoftDeletes) para conservar el historial. */
    public function destroy(Paciente $paciente): RedirectResponse
    {
        $paciente->delete();

        return redirect()->route('pacientes.index')
            ->with('exito', 'Paciente eliminado correctamente.');
    }

    /**
     * Reglas de validación para crear o actualizar un paciente.
     *
     * @return array<string, mixed>
     */
    private function reglas(?Paciente $paciente = null): array
    {
        return [
            'tipo_documento' => ['required', Rule::in(array_keys(Paciente::TIPOS_DOCUMENTO))],
            'documento' => ['required', 'string', 'max:20', Rule::unique('pacientes', 'documento')->ignore($paciente)],
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:today'],
            'sexo' => ['required', Rule::in(array_keys(Paciente::SEXOS))],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'eps' => ['nullable', 'string', 'max:100'],
            'grupo_sanguineo' => ['nullable', Rule::in(Paciente::GRUPOS_SANGUINEOS)],
        ];
    }
}
