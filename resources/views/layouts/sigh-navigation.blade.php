@php
$navRoles = \Illuminate\Support\Facades\DB::table('sigh2_roles')->where('user_id',auth()->id())->pluck('role')->all();
$navModules = config('sigh2', []);
$groups = [
 'Atención asistencial'=>['pacientes','medicos','especialidades','citas','consultas','admisiones','notas','triage','estancias','ordenes','resultados'],
 'Aseguramiento'=>['pagadores','afiliaciones','contratos','tarifas','autorizaciones'],
 'Farmacia y almacén'=>['dispensaciones','productos','lotes','bodegas','proveedores','compras','movimientos'],
 'Gestión financiera'=>['prestaciones','facturas','rips','glosas','pagos'],
 'Administración'=>['sedes','servicios','camas','personal','turnos','equipos','mantenimientos','parametros','audit']];
$legacy=['pacientes'=>'Pacientes','medicos'=>'Médicos','especialidades'=>'Especialidades','citas'=>'Citas y agenda','consultas'=>'Consultas'];
$current=request()->route('key');
@endphp
<aside id="sidebar" class="sidebar"><a class="brand" href="{{ route('sigh2.home') }}"><span class="brand-icon">✚</span><span>SIGH <small>2.0</small><em>Gestión integral hospitalaria</em></span></a>
<div class="nav-scroll"><span class="nav-caption">ESPACIO DE TRABAJO</span><a class="nav-link {{ request()->routeIs('sigh2.home','dashboard') ? 'active' : '' }}" href="{{ route('sigh2.home') }}">▦ <span>Panel principal</span></a>
@foreach($groups as $group=>$keys)
@php($visible=array_filter($keys,fn($k)=>isset($legacy[$k]) || ($k==='audit' ? in_array('admin',$navRoles) : isset($navModules[$k]) && in_array($navModules[$k]['role'],$navRoles))))
@if(count($visible))<details class="nav-group" @if(in_array($current,$visible) || collect($visible)->contains(fn($k)=>request()->routeIs($k.'.*')) || ($group==='Administración' && request()->routeIs('sigh2.audit'))) open @endif><summary><span>{{ ['Atención asistencial'=>'✚','Aseguramiento'=>'◇','Farmacia y almacén'=>'▤','Gestión financiera'=>'▥','Administración'=>'⚙'][$group] }}</span>{{ $group }}</summary>
@foreach($visible as $k)
@php($url=isset($legacy[$k])?route($k.'.index'):($k==='audit'?route('sigh2.audit'):route('sigh2.index',$k)))
<a class="nav-link sub {{ ($current===$k || request()->routeIs($k.'.*') || ($k==='audit' && request()->routeIs('sigh2.audit'))) ? 'active' : '' }}" href="{{ $url }}">{{ $legacy[$k] ?? ($k==='audit'?'Auditoría':$navModules[$k]['title']) }}</a>
@endforeach</details>@endif
@endforeach</div><div class="sidebar-footer"><span class="status-dot"></span> Entorno académico<form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-sec" type="submit">Cerrar sesión</button></form></div></aside>