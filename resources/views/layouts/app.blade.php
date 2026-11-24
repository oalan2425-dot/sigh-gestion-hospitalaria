<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Inicio') - SIGH</title>
    <link rel="stylesheet" href="{{ asset('css/sigh.css') }}">
</head>
<body>
@auth
    <header class="barra">
        <span class="marca">SIGH</span>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'activo' : '' }}">Panel</a>
        <a href="{{ route('pacientes.index') }}" class="{{ request()->routeIs('pacientes.*') ? 'activo' : '' }}">Pacientes</a>
        <a href="{{ route('medicos.index') }}" class="{{ request()->routeIs('medicos.*') ? 'activo' : '' }}">Médicos</a>
        <a href="{{ route('especialidades.index') }}" class="{{ request()->routeIs('especialidades.*') ? 'activo' : '' }}">Especialidades</a>
        <a href="{{ route('citas.index') }}" class="{{ request()->routeIs('citas.*') ? 'activo' : '' }}">Citas</a>
        <a href="{{ route('consultas.index') }}" class="{{ request()->routeIs('consultas.*') ? 'activo' : '' }}">Consultas</a>
        <a href="{{ route('sigh2.home') }}">SIGH 2.0</a>
        <div class="usuario">
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-sec btn-peq">Cerrar sesión</button>
            </form>
        </div>
    </header>
@endauth

<main class="contenedor">
    @if (session('exito'))
        <div class="alerta exito">{{ session('exito') }}</div>
    @endif
    @if (session('error'))
        <div class="alerta error-global">{{ session('error') }}</div>
    @endif

    @yield('contenido')
</main>
</body>
</html>
