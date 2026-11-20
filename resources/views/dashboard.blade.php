@extends('layouts.app')

@section('titulo', 'Panel')

@section('contenido')
<h1>Panel principal</h1>

<div class="indicadores">
    <div class="indicador"><div class="numero">{{ $indicadores['pacientes'] }}</div><div class="texto">Pacientes registrados</div></div>
    <div class="indicador"><div class="numero">{{ $indicadores['medicos'] }}</div><div class="texto">Médicos activos</div></div>
    <div class="indicador"><div class="numero">{{ $indicadores['citas_hoy'] }}</div><div class="texto">Citas programadas hoy</div></div>
    <div class="indicador"><div class="numero">{{ $indicadores['citas_pendientes'] }}</div><div class="texto">Citas pendientes</div></div>
</div>

<h2>Próximas citas</h2>
<table>
    <thead><tr><th>Fecha y hora</th><th>Paciente</th><th>Médico</th><th>Especialidad</th><th>Motivo</th></tr></thead>
    <tbody>
    @forelse ($proximasCitas as $cita)
        <tr>
            <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
            <td>{{ $cita->paciente->nombre_completo }}</td>
            <td>{{ $cita->medico->nombre_completo }}</td>
            <td>{{ $cita->medico->especialidad->nombre }}</td>
            <td>{{ $cita->motivo }}</td>
        </tr>
    @empty
        <tr><td colspan="5" class="vacio">No hay citas programadas.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
