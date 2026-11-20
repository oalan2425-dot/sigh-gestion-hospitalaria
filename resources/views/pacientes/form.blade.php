@extends('layouts.app')

@section('titulo', $paciente->exists ? 'Editar paciente' : 'Nuevo paciente')

@section('contenido')
<h1>{{ $paciente->exists ? 'Editar paciente' : 'Nuevo paciente' }}</h1>

<form method="POST" class="formulario"
      action="{{ $paciente->exists ? route('pacientes.update', $paciente) : route('pacientes.store') }}">
    @csrf
    @if ($paciente->exists)
        @method('PUT')
    @endif

    @include('partials.seleccion', ['nombre' => 'tipo_documento', 'etiqueta' => 'Tipo de documento', 'opciones' => App\Models\Paciente::TIPOS_DOCUMENTO, 'valor' => $paciente->tipo_documento ?? 'CC', 'requerido' => true])
    @include('partials.campo', ['nombre' => 'documento', 'etiqueta' => 'Número de documento', 'valor' => $paciente->documento, 'requerido' => true])
    @include('partials.campo', ['nombre' => 'nombres', 'etiqueta' => 'Nombres', 'valor' => $paciente->nombres, 'requerido' => true])
    @include('partials.campo', ['nombre' => 'apellidos', 'etiqueta' => 'Apellidos', 'valor' => $paciente->apellidos, 'requerido' => true])
    @include('partials.campo', ['nombre' => 'fecha_nacimiento', 'etiqueta' => 'Fecha de nacimiento', 'tipo' => 'date', 'valor' => $paciente->fecha_nacimiento?->format('Y-m-d'), 'requerido' => true])
    @include('partials.seleccion', ['nombre' => 'sexo', 'etiqueta' => 'Sexo', 'opciones' => App\Models\Paciente::SEXOS, 'valor' => $paciente->sexo, 'requerido' => true])
    @include('partials.campo', ['nombre' => 'telefono', 'etiqueta' => 'Teléfono', 'valor' => $paciente->telefono])
    @include('partials.campo', ['nombre' => 'email', 'etiqueta' => 'Correo electrónico', 'tipo' => 'email', 'valor' => $paciente->email])
    @include('partials.campo', ['nombre' => 'direccion', 'etiqueta' => 'Dirección', 'valor' => $paciente->direccion])
    @include('partials.campo', ['nombre' => 'eps', 'etiqueta' => 'EPS / Aseguradora', 'valor' => $paciente->eps])
    @include('partials.seleccion', ['nombre' => 'grupo_sanguineo', 'etiqueta' => 'Grupo sanguíneo', 'opciones' => array_combine(App\Models\Paciente::GRUPOS_SANGUINEOS, App\Models\Paciente::GRUPOS_SANGUINEOS), 'valor' => $paciente->grupo_sanguineo])

    <div class="pie-form">
        <button type="submit" class="btn">Guardar</button>
        <a href="{{ route('pacientes.index') }}" class="btn btn-sec">Cancelar</a>
    </div>
</form>
@endsection
