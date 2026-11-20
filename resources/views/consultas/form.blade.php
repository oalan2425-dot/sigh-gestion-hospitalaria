@extends('layouts.app')

@section('titulo', $consulta->exists ? 'Editar consulta' : 'Nueva consulta')

@section('contenido')
<h1>{{ $consulta->exists ? 'Editar consulta' : 'Nueva consulta' }}</h1>

<form method="POST" class="formulario"
      action="{{ $consulta->exists ? route('consultas.update', $consulta) : route('consultas.store') }}">
    @csrf
    @if ($consulta->exists)
        @method('PUT')
    @endif

    @include('partials.seleccion', ['nombre' => 'paciente_id', 'etiqueta' => 'Paciente', 'opciones' => $pacientes->mapWithKeys(fn ($p) => [$p->id => $p->nombre_completo . ' (' . $p->documento . ')']), 'valor' => $consulta->paciente_id, 'requerido' => true])
    @include('partials.seleccion', ['nombre' => 'medico_id', 'etiqueta' => 'Médico', 'opciones' => $medicos->mapWithKeys(fn ($m) => [$m->id => $m->nombre_completo]), 'valor' => $consulta->medico_id, 'requerido' => true])
    @include('partials.campo', ['nombre' => 'fecha_consulta', 'etiqueta' => 'Fecha y hora', 'tipo' => 'datetime-local', 'valor' => $consulta->fecha_consulta?->format('Y-m-d\TH:i'), 'requerido' => true])
    @include('partials.campo', ['nombre' => 'motivo', 'etiqueta' => 'Motivo de consulta', 'valor' => $consulta->motivo, 'requerido' => true, 'ancho' => true])
    @include('partials.area', ['nombre' => 'diagnostico', 'etiqueta' => 'Diagnóstico', 'valor' => $consulta->diagnostico, 'requerido' => true])
    @include('partials.area', ['nombre' => 'tratamiento', 'etiqueta' => 'Tratamiento', 'valor' => $consulta->tratamiento])

    <div class="pie-form">
        <button type="submit" class="btn">Guardar</button>
        <a href="{{ route('consultas.index') }}" class="btn btn-sec">Cancelar</a>
    </div>
</form>
@endsection
