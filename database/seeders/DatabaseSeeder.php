<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Pobla la base de datos con el usuario administrador y datos de demostración.
     */
    public function run(): void
    {
        // Usuario para ingresar al sistema: admin@sigh.test / password
        User::updateOrCreate(
            ['email' => 'admin@sigh.test'],
            ['name' => 'Administrador SIGH', 'password' => Hash::make('password')]
        );

        $this->call(HospitalSeeder::class);
    }
}
