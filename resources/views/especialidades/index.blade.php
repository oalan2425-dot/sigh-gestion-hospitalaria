@extends('layouts.app')

@section('titulo', 'Especialidades')

@section('contenido')
<div class="acciones-superiores">
    <h1>Especialidades</h1>
    <a href="{{ route('especialidades.create') }}" class="btn">Nueva especialidad</a>
</div>

<table>
    <thead><tr><th>Nombre</th><th>Descripción</th><th>Médicos</th><th>Acciones</th></tr></thead>
    <tbody>
    @forelse ($especialidades as $especialidad)
        <tr>
            <td>{{ $especialidad->nombre }}</td>
            <td>{{ $especialidad->descripcion }}</td>
            <td>{{ $especialidad->medicos_count }}</td>
            <td>
                <a href="{{ route('especialidades.edit', $especialidad) }}" class="btn btn-sec btn-peq">Editar</a>
                <form method="POST" action="{{ route('especialidades.destroy', $especialidad) }}" class="inline"
                      onsubmit="return confirm('¿Eliminar esta especialidad?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-rojo btn-peq">Eliminar</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="4" class="vacio">No hay especialidades registradas.</td></tr>
    @endforelse
    </tbody>
</table>

{{ $especialidades->links('pagination::bootstrap-5') }}
@endsection
