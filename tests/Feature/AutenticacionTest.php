<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutenticacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visitante_es_redirigido_al_login(): void
    {
        $this->get('/panel')->assertRedirect('/login');
        $this->get('/pacientes')->assertRedirect('/login');
    }

    public function test_el_login_muestra_el_formulario(): void
    {
        $this->get('/login')->assertOk()->assertSee('Iniciar sesión');
    }

    public function test_un_usuario_puede_iniciar_sesion(): void
    {
        $usuario = User::factory()->create(['password' => bcrypt('secreto123')]);

        $this->post('/login', ['email' => $usuario->email, 'password' => 'secreto123'])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($usuario);
    }

    public function test_credenciales_invalidas_no_inician_sesion(): void
    {
        $usuario = User::factory()->create();

        $this->post('/login', ['email' => $usuario->email, 'password' => 'incorrecta'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

   public function test_el_panel_carga_para_un_usuario_autenticado(): void
{
    $this->actingAs(User::factory()->create());

    $this->get('/panel')
        ->assertRedirect('/sigh2');

    $this->get('/sigh2')
        ->assertOk()
        ->assertSee('Panel principal');
}
}
