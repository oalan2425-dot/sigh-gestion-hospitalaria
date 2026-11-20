@extends('layouts.app')

@section('titulo', 'Médicos')

@section('contenido')
<div class="acciones-superiores">
    <h1>Médicos</h1>
    <a href="{{ route('medicos.create') }}" class="btn">Nuevo médico</a>
</div>

<table>
    <thead><tr><th>Documento</th><th>Médico</th><th>Registro médico</th><th>Especialidad</th><th>Estado</th><th>Acciones</th></tr></thead>
    <tbody>
    @forelse ($medicos as $medico)
        <tr>
            <td>{{ $medico->documento }}</td>
            <td>{{ $medico->nombre_completo }}</td>
            <td>{{ $medico->registro_medico }}</td>
            <td>{{ $medico->especialidad->nombre }}</td>
            <td><span class="etiqueta {{ $medico->activo ? '' : 'cancelada' }}">{{ $medico->activo ? 'Activo' : 'Inactivo' }}</span></td>
            <td>
                <a href="{{ route('medicos.edit', $medico) }}" class="btn btn-sec btn-peq">Editar</a>
                <form method="POST" action="{{ route('medicos.destroy', $medico) }}" class="inline"
                      onsubmit="return confirm('¿Eliminar este médico?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-rojo btn-peq">Eliminar</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="vacio">No hay médicos registrados.</td></tr>
    @endforelse
    </tbody>
</table>

{{ $medicos->links('pagination::bootstrap-5') }}
@endsection
