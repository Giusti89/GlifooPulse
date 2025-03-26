<?php

use App\Http\Controllers\InicioController;
use App\Http\Controllers\PlanesController;
use Illuminate\Support\Facades\Route;



Route::controller(InicioController::class)->group(function () {
    Route::get('/', 'index')
        ->name('inicio');        
});
Route::get('./admin/login', function () {
    return redirect('/admin/login'); 
})->name('custom.filament.login');



Route::controller(PlanesController::class)->group(function () {
    Route::get('/productos/planes', 'index')
        ->name('planes');        
});

Route::get('./admin/register/{id}', function () {
    return redirect('custom.filament.register'); 
})->name('registro');

Route::middleware('auth')->group(function () {
    Route::get('./admin', function () {
        return redirect('/admin'); 
    })->name('admin');
    
});

