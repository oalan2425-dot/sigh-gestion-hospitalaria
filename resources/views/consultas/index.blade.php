@extends('layouts.app')

@section('titulo', 'Consultas')

@section('contenido')
<div class="acciones-superiores">
    <h1>Consultas médicas</h1>
    <a href="{{ route('consultas.create') }}" class="btn">Nueva consulta</a>
</div>

<table>
    <thead><tr><th>Fecha</th><th>Paciente</th><th>Médico</th><th>Motivo</th><th>Diagnóstico</th><th>Acciones</th></tr></thead>
    <tbody>
    @forelse ($consultas as $consulta)
        <tr>
            <td>{{ $consulta->fecha_consulta->format('d/m/Y H:i') }}</td>
            <td>{{ $consulta->paciente->nombre_completo }}</td>
            <td>{{ $consulta->medico->nombre_completo }}</td>
            <td>{{ $consulta->motivo }}</td>
            <td>{{ \Illuminate\Support\Str::limit($consulta->diagnostico, 60) }}</td>
            <td>
                <a href="{{ route('consultas.edit', $consulta) }}" class="btn btn-sec btn-peq">Editar</a>
                <form method="POST" action="{{ route('consultas.destroy', $consulta) }}" class="inline"
                      onsubmit="return confirm('¿Eliminar esta consulta?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-rojo btn-peq">Eliminar</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="vacio">No hay consultas registradas.</td></tr>
    @endforelse
    </tbody>
</table>

{{ $consultas->links('pagination::bootstrap-5') }}
@endsection
