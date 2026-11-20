<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * CRUD de especialidades médicas.
 */
class EspecialidadController extends Controller
{
    public function index(): View
    {
        $especialidades = Especialidad::withCount('medicos')
            ->orderBy('nombre')
            ->paginate(10);

        return view('especialidades.index', compact('especialidades'));
    }

    public function create(): View
    {
        return view('especialidades.form', ['especialidad' => new Especialidad()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Especialidad::create($request->validate($this->reglas()));

        return redirect()->route('especialidades.index')
            ->with('exito', 'Especialidad creada correctamente.');
    }

    public function edit(Especialidad $especialidad): View
    {
        return view('especialidades.form', compact('especialidad'));
    }

    public function update(Request $request, Especialidad $especialidad): RedirectResponse
    {
        $especialidad->update($request->validate($this->reglas($especialidad)));

        return redirect()->route('especialidades.index')
            ->with('exito', 'Especialidad actualizada correctamente.');
    }

    /** No se puede eliminar una especialidad que tiene médicos asociados. */
    public function destroy(Especialidad $especialidad): RedirectResponse
    {
        if ($especialidad->medicos()->exists()) {
            return redirect()->route('especialidades.index')
                ->with('error', 'No se puede eliminar: la especialidad tiene médicos asociados.');
        }

        $especialidad->delete();

        return redirect()->route('especialidades.index')
            ->with('exito', 'Especialidad eliminada correctamente.');
    }

    /**
     * @return array<string, mixed>
     */
    private function reglas(?Especialidad $especialidad = null): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100', Rule::unique('especialidades', 'nombre')->ignore($especialidad)],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ];
    }
}
