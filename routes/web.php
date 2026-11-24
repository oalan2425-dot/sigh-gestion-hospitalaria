<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas web del SIGH (Sistema Integral de Gestión Hospitalaria)
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/panel');

// Rutas para visitantes (sin sesión iniciada)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Rutas protegidas: requieren iniciar sesión
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/panel', fn () => redirect()->route('sigh2.home'))->name('dashboard');

    Route::resource('pacientes', PacienteController::class);
    Route::resource('medicos', MedicoController::class)->except('show');
    Route::resource('especialidades', EspecialidadController::class)
        ->parameters(['especialidades' => 'especialidad'])
        ->except('show');
    Route::resource('citas', CitaController::class)->except('show');
    Route::resource('consultas', ConsultaController::class)->except('show');
});

require __DIR__.'/sigh2.php';
