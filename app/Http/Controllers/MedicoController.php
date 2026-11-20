<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Models\Medico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * CRUD de médicos.
 */
class MedicoController extends Controller
{
    public function index(): View
    {
        $medicos = Medico::with('especialidad')
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->paginate(10);

        return view('medicos.index', compact('medicos'));
    }

    public function create(): View
    {
        return view('medicos.form', [
            'medico' => new Medico(['activo' => true]),
            'especialidades' => Especialidad::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglas());
        $datos['activo'] = $request->boolean('activo');

        Medico::create($datos);

        return redirect()->route('medicos.index')
            ->with('exito', 'Médico registrado correctamente.');
    }

    public function edit(Medico $medico): View
    {
        return view('medicos.form', [
            'medico' => $medico,
            'especialidades' => Especialidad::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Medico $medico): RedirectResponse
    {
        $datos = $request->validate($this->reglas($medico));
        $datos['activo'] = $request->boolean('activo');

        $medico->update($datos);

        return redirect()->route('medicos.index')
            ->with('exito', 'Médico actualizado correctamente.');
    }

    public function destroy(Medico $medico): RedirectResponse
    {
        $medico->delete();

        return redirect()->route('medicos.index')
            ->with('exito', 'Médico eliminado correctamente.');
    }

    /**
     * @return array<string, mixed>
     */
    private function reglas(?Medico $medico = null): array
    {
        return [
            'documento' => ['required', 'string', 'max:20', Rule::unique('medicos', 'documento')->ignore($medico)],
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'registro_medico' => ['required', 'string', 'max:30', Rule::unique('medicos', 'registro_medico')->ignore($medico)],
            'especialidad_id' => ['required', Rule::exists('especialidades', 'id')],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
        ];
    }
}
