<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /** La raíz del sitio redirige al panel (que a su vez pide iniciar sesión). */
    public function test_la_raiz_redirige_al_panel(): void
    {
        $this->get('/')->assertRedirect('/panel');
    }
}
