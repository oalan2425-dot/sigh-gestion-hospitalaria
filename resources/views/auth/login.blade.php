@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
<div class="login tarjeta">
    <h1>SIGH - Iniciar sesión</h1>
    <p>Sistema Integral de Gestión Hospitalaria</p>

    <form method="POST" action="{{ route('login') }}" class="campo" style="gap:1rem">
        @csrf
        <div class="campo">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div class="campo">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <label><input type="checkbox" name="recordar" value="1"> Recordarme</label>
        <button type="submit" class="btn">Ingresar</button>
    </form>
</div>
@endsection
