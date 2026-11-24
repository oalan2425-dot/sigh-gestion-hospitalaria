<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sigh2:rol {email} {rol}', function(){
 $roles=array_unique(array_column(config('sigh2'),'role')); if(!in_array($this->argument('rol'),$roles)){ $this->error('Rol inválido: '.implode(', ',$roles));return 1; }
 $id=\Illuminate\Support\Facades\DB::table('users')->where('email',$this->argument('email'))->value('id');if(!$id){$this->error('Usuario inexistente');return 1;}
 \Illuminate\Support\Facades\DB::table('sigh2_roles')->insertOrIgnore(['user_id'=>$id,'role'=>$this->argument('rol')]);$this->info('Rol asignado');return 0;
});
