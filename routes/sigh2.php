<?php
use App\Http\Controllers\Sigh2Controller;
use Illuminate\Support\Facades\Route;
Route::middleware('auth')->prefix('sigh2')->name('sigh2.')->group(function(){
Route::get('/',[Sigh2Controller::class,'home'])->name('home');
Route::get('/auditoria',[Sigh2Controller::class,'auditLog'])->name('audit');
Route::get('/{key}',[Sigh2Controller::class,'index'])->name('index');
Route::get('/{key}/crear',[Sigh2Controller::class,'create'])->name('create');
Route::post('/{key}',[Sigh2Controller::class,'store'])->name('store');});
