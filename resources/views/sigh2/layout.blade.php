@extends('layouts.app')
@section('contenido')
@if($errors->any())<div class="alerta error-global" role="alert"><strong>Revisa los datos ingresados.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('body')
@endsection