@extends('layouts.app')

@section('titulo', 'Ficha del paciente')

@section('contenido')
<div class="acciones-superiores">
    <h1>{{ $paciente->nombre_completo }}</h1>
    <div>
        <a href="{{ route('consultas.create', ['paciente_id' => $paciente->id]) }}" class="btn">Registrar consulta</a>
        <a href="{{ route('pacientes.index') }}" class="btn btn-sec">Volver</a>
    </div>
</div>

<div class="tarjeta">
    <p><strong>Documento:</strong> {{ $paciente->tipo_documento }} {{ $paciente->documento }}</p>
    <p><strong>Edad:</strong> {{ $paciente->edad }} años &nbsp; <strong>Sexo:</strong> {{ App\Models\Paciente::SEXOS[$paciente->sexo] ?? $paciente->sexo }} &nbsp; <strong>Grupo sanguíneo:</strong> {{ $paciente->grupo_sanguineo ?? 'N/D' }}</p>
    <p><strong>Teléfono:</strong> {{ $paciente->telefono ?? 'N/D' }} &nbsp; <strong>Correo:</strong> {{ $paciente->email ?? 'N/D' }}</p>
    <p><strong>Dirección:</strong> {{ $paciente->direccion ?? 'N/D' }} &nbsp; <strong>EPS:</strong> {{ $paciente->eps ?? 'N/D' }}</p>
</div>

<h2>Citas</h2>
<table>
    <thead><tr><th>Fecha y hora</th><th>Médico</th><th>Motivo</th><th>Estado</th></tr></thead>
    <tbody>
    @forelse ($paciente->citas as $cita)
        <tr>
            <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
            <td>{{ $cita->medico->nombre_completo }}</td>
            <td>{{ $cita->motivo }}</td>
            <td><span class="etiqueta {{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td>
        </tr>
    @empty
        <tr><td colspan="4" class="vacio">Sin citas.</td></tr>
    @endforelse
    </tbody>
</table>

<h2>Historial de consultas</h2>
<table>
    <thead><tr><th>Fecha</th><th>Médico</th><th>Motivo</th><th>Diagnóstico</th><th>Tratamiento</th></tr></thead>
    <tbody>
    @forelse ($paciente->consultas as $consulta)
        <tr>
            <td>{{ $consulta->fecha_consulta->format('d/m/Y H:i') }}</td>
            <td>{{ $consulta->medico->nombre_completo }}</td>
            <td>{{ $consulta->motivo }}</td>
            <td>{{ $consulta->diagnostico }}</td>
            <td>{{ $consulta->tratamiento }}</td>
        </tr>
    @empty
        <tr><td colspan="5" class="vacio">Sin consultas registradas.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
