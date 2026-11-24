<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="color-scheme" content="dark"><title>@yield('titulo', 'Gestión hospitalaria') · SIGH</title><link rel="stylesheet" href="{{ asset('css/sigh-dark.css') }}?v=2"></head>
<body class="{{ auth()->check() ? 'authenticated' : 'guest' }}">
@auth
@include('layouts.sigh-navigation')
<div class="workspace"><header class="topbar"><button class="menu-toggle" type="button" aria-controls="sidebar" aria-expanded="false" aria-label="Abrir menú">☰</button><span class="breadcrumb">SIGH / Gestión institucional</span><div class="profile"><span class="avatar">{{ mb_substr(auth()->user()->name,0,1) }}</span><span>{{ auth()->user()->name }}<small>Sesión activa</small></span></div></header>
@endauth
<main class="contenedor" id="contenido">
@if(session('exito'))<div class="alerta exito" role="status">{{ session('exito') }}</div>@endif
@if(session('error'))<div class="alerta error-global" role="alert">{{ session('error') }}</div>@endif
@yield('contenido')
</main>
@auth</div>@endauth
<script src="{{ asset('js/sigh-dark.js') }}" defer></script></body></html>