@extends('layouts.app')

@section('titulo', 'Pacientes')

@section('contenido')
<div class="acciones-superiores">
    <h1>Pacientes</h1>
    <form method="GET" action="{{ route('pacientes.index') }}" class="buscador">
        <input type="search" name="buscar" value="{{ $buscar }}" placeholder="Buscar por nombre o documento">
        <button type="submit" class="btn btn-sec">Buscar</button>
        <a href="{{ route('pacientes.create') }}" class="btn">Nuevo paciente</a>
    </form>
</div>

<table>
    <thead><tr><th>Documento</th><th>Paciente</th><th>Edad</th><th>Teléfono</th><th>EPS</th><th>Acciones</th></tr></thead>
    <tbody>
    @forelse ($pacientes as $paciente)
        <tr>
            <td>{{ $paciente->tipo_documento }} {{ $paciente->documento }}</td>
            <td>{{ $paciente->nombre_completo }}</td>
            <td>{{ $paciente->edad }}</td>
            <td>{{ $paciente->telefono }}</td>
            <td>{{ $paciente->eps }}</td>
            <td>
                <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-sec btn-peq">Ver</a>
                <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-sec btn-peq">Editar</a>
                <form method="POST" action="{{ route('pacientes.destroy', $paciente) }}" class="inline"
                      onsubmit="return confirm('¿Eliminar este paciente?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-rojo btn-peq">Eliminar</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="vacio">No hay pacientes registrados.</td></tr>
    @endforelse
    </tbody>
</table>

{{ $pacientes->links('pagination::bootstrap-5') }}
@endsection
