@extends('layouts.app')

@section('titulo', $medico->exists ? 'Editar médico' : 'Nuevo médico')

@section('contenido')
<h1>{{ $medico->exists ? 'Editar médico' : 'Nuevo médico' }}</h1>

<form method="POST" class="formulario"
      action="{{ $medico->exists ? route('medicos.update', $medico) : route('medicos.store') }}">
    @csrf
    @if ($medico->exists)
        @method('PUT')
    @endif

    @include('partials.campo', ['nombre' => 'documento', 'etiqueta' => 'Documento', 'valor' => $medico->documento, 'requerido' => true])
    @include('partials.campo', ['nombre' => 'nombres', 'etiqueta' => 'Nombres', 'valor' => $medico->nombres, 'requerido' => true])
    @include('partials.campo', ['nombre' => 'apellidos', 'etiqueta' => 'Apellidos', 'valor' => $medico->apellidos, 'requerido' => true])
    @include('partials.campo', ['nombre' => 'registro_medico', 'etiqueta' => 'Registro médico', 'valor' => $medico->registro_medico, 'requerido' => true])
    @include('partials.seleccion', ['nombre' => 'especialidad_id', 'etiqueta' => 'Especialidad', 'opciones' => $especialidades->pluck('nombre', 'id'), 'valor' => $medico->especialidad_id, 'requerido' => true])
    @include('partials.campo', ['nombre' => 'telefono', 'etiqueta' => 'Teléfono', 'valor' => $medico->telefono])
    @include('partials.campo', ['nombre' => 'email', 'etiqueta' => 'Correo electrónico', 'tipo' => 'email', 'valor' => $medico->email])

    <div class="campo">
        <label>
            <input type="checkbox" name="activo" value="1" @checked(old('activo', $medico->exists ? $medico->activo : true))>
            Médico activo
        </label>
    </div>

    <div class="pie-form">
        <button type="submit" class="btn">Guardar</button>
        <a href="{{ route('medicos.index') }}" class="btn btn-sec">Cancelar</a>
    </div>
</form>
@endsection
