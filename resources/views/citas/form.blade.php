@extends('layouts.app')

@section('titulo', $cita->exists ? 'Editar cita' : 'Nueva cita')

@section('contenido')
<h1>{{ $cita->exists ? 'Editar cita' : 'Nueva cita' }}</h1>

<form method="POST" class="formulario"
      action="{{ $cita->exists ? route('citas.update', $cita) : route('citas.store') }}">
    @csrf
    @if ($cita->exists)
        @method('PUT')
    @endif

    @include('partials.seleccion', ['nombre' => 'paciente_id', 'etiqueta' => 'Paciente', 'opciones' => $pacientes->mapWithKeys(fn ($p) => [$p->id => $p->nombre_completo . ' (' . $p->documento . ')']), 'valor' => $cita->paciente_id, 'requerido' => true])
    @include('partials.seleccion', ['nombre' => 'medico_id', 'etiqueta' => 'Médico', 'opciones' => $medicos->mapWithKeys(fn ($m) => [$m->id => $m->nombre_completo . ' - ' . $m->especialidad->nombre]), 'valor' => $cita->medico_id, 'requerido' => true])
    @include('partials.campo', ['nombre' => 'fecha_hora', 'etiqueta' => 'Fecha y hora', 'tipo' => 'datetime-local', 'valor' => $cita->fecha_hora?->format('Y-m-d\TH:i'), 'requerido' => true])
    @include('partials.seleccion', ['nombre' => 'estado', 'etiqueta' => 'Estado', 'opciones' => array_combine(App\Models\Cita::ESTADOS, array_map('ucfirst', App\Models\Cita::ESTADOS)), 'valor' => $cita->estado ?? 'programada', 'requerido' => true])
    @include('partials.campo', ['nombre' => 'motivo', 'etiqueta' => 'Motivo de la cita', 'valor' => $cita->motivo, 'requerido' => true, 'ancho' => true])
    @include('partials.area', ['nombre' => 'observaciones', 'etiqueta' => 'Observaciones', 'valor' => $cita->observaciones])

    <div class="pie-form">
        <button type="submit" class="btn">Guardar</button>
        <a href="{{ route('citas.index') }}" class="btn btn-sec">Cancelar</a>
    </div>
</form>
@endsection
