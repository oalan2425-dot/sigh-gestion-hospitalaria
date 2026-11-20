@extends('layouts.app')

@section('titulo', 'Citas')

@section('contenido')
<div class="acciones-superiores">
    <h1>Citas médicas</h1>
    <form method="GET" action="{{ route('citas.index') }}" class="buscador">
        <select name="estado" onchange="this.form.submit()" style="width:auto">
            <option value="">Todos los estados</option>
            @foreach (App\Models\Cita::ESTADOS as $e)
                <option value="{{ $e }}" @selected($estado === $e)>{{ ucfirst($e) }}</option>
            @endforeach
        </select>
        <a href="{{ route('citas.create') }}" class="btn">Nueva cita</a>
    </form>
</div>

<table>
    <thead><tr><th>Fecha y hora</th><th>Paciente</th><th>Médico</th><th>Motivo</th><th>Estado</th><th>Acciones</th></tr></thead>
    <tbody>
    @forelse ($citas as $cita)
        <tr>
            <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
            <td>{{ $cita->paciente->nombre_completo }}</td>
            <td>{{ $cita->medico->nombre_completo }}<br><small>{{ $cita->medico->especialidad->nombre }}</small></td>
            <td>{{ $cita->motivo }}</td>
            <td><span class="etiqueta {{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td>
            <td>
                <a href="{{ route('citas.edit', $cita) }}" class="btn btn-sec btn-peq">Editar</a>
                <form method="POST" action="{{ route('citas.destroy', $cita) }}" class="inline"
                      onsubmit="return confirm('¿Eliminar esta cita?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-rojo btn-peq">Eliminar</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="vacio">No hay citas.</td></tr>
    @endforelse
    </tbody>
</table>

{{ $citas->links('pagination::bootstrap-5') }}
@endsection
