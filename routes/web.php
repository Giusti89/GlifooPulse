<?php

use App\Http\Controllers\InicioController;
use App\Http\Controllers\PlanesController;
use App\Http\Controllers\PublicidadController;
use App\Http\Controllers\SocioController;
use Illuminate\Support\Facades\Route;



Route::controller(InicioController::class)->group(function () {
    Route::get('/', 'index')
        ->name('inicio');
});



Route::get('./usuario/login', function () {
    return redirect('/usuario/login');
})->name('usuariologin');



Route::controller(PlanesController::class)->group(function () {
    Route::get('/productos/planes', 'index')
        ->name('planes');
});

Route::controller(SocioController::class)->group(function () {
    Route::get('/socios/index', 'index')
        ->name('socios');

    Route::get('/socios/{slug}', 'show')
        ->name('slug');
});

Route::controller(PublicidadController::class)->group(function () {
    Route::get('/{slug}', 'show')
        ->name('publicidad');
    Route::get('/enlace/{id}','redirecion')
    ->name('redireccion');

   
});

Route::get('/usuario/register/{paquete?}', function ($paquete = null) {
    return redirect()->route('filament.usuario.auth.register', ['paquete' => $paquete]);
})->name('registro');


Route::middleware('auth')->group(function () {
    Route::get('./admin', function () {
        return redirect('/admin');
    })->name('admin');
});
