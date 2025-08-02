<?php

use App\Http\Controllers\InicioController;
use App\Http\Controllers\PlanesController;
use App\Http\Controllers\PublicidadController;
use App\Http\Controllers\RenovacionController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\VentasController;
use Illuminate\Support\Facades\Route;
use App\Livewire\RenovacionForm;


Route::controller(InicioController::class)->group(function () {
    Route::get('/', 'index')
        ->name('inicio');
});

Route::controller(RenovacionController::class)->group(function () {
    Route::get('/resuscripcion/{renovacion}', 'create')
        ->name('resuscrip');

    Route::post('/resuscripcion/{renovacion}', 'store')
        ->name('resuscripcion.store');
});

Route::get('./usuario/login', function () {
    return redirect('/usuario/login');
})->name('usuariologin');


Route::get('/renovacion', RenovacionForm::class)->name('renovacion.form');


Route::controller(PlanesController::class)->group(function () {
    Route::get('/productos', 'index')
        ->name('planes');
});

Route::controller(SocioController::class)->group(function () {
    Route::get('/socios', 'index')
        ->name('socios');

    Route::get('/socios/{slug}', 'show')
        ->name('slug');
});

Route::controller(PublicidadController::class)->group(function () {
    Route::get('/{slug}', 'show')
        ->middleware('check.suscripcion')
        ->name('publicidad');

    Route::get('/enlace/{id}', 'redirecion')
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
