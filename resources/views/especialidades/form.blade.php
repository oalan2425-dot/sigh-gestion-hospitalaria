@extends('layouts.app')

@section('titulo', $especialidad->exists ? 'Editar especialidad' : 'Nueva especialidad')

@section('contenido')
<h1>{{ $especialidad->exists ? 'Editar especialidad' : 'Nueva especialidad' }}</h1>

<form method="POST" class="formulario"
      action="{{ $especialidad->exists ? route('especialidades.update', $especialidad) : route('especialidades.store') }}">
    @csrf
    @if ($especialidad->exists)
        @method('PUT')
    @endif

    @include('partials.campo', ['nombre' => 'nombre', 'etiqueta' => 'Nombre', 'valor' => $especialidad->nombre, 'requerido' => true])
    @include('partials.campo', ['nombre' => 'descripcion', 'etiqueta' => 'Descripción', 'valor' => $especialidad->descripcion])

    <div class="pie-form">
        <button type="submit" class="btn">Guardar</button>
        <a href="{{ route('especialidades.index') }}" class="btn btn-sec">Cancelar</a>
    </div>
</form>
@endsection
