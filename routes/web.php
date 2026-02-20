<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CorteController;
use App\Http\Controllers\PizzaController;
use App\Http\Controllers\AlitasController;
use App\Http\Controllers\CostillasController;
use App\Http\Controllers\HamburguesasController;
use App\Http\Controllers\MagnoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirección inicial
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

// --- RUTAS DE AUTENTICACIÓN ---
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// --- RUTAS PROTEGIDAS
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- MÓDULO DE EMPLEADOS ---
    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
    Route::get('/empleados/crear', [EmpleadoController::class, 'create'])->name('empleados.create');
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
    Route::get('/empleados/{id}/editar', [EmpleadoController::class, 'edit'])->name('empleados.edit');
    Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');
    Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');
    Route::patch('/empleados/{id}/estado', [EmpleadoController::class, 'toggleStatus'])->name('empleados.status');

    // --- MÓDULO DE CORTE MENSUAL ---
    Route::get('/corte-mensual', [CorteController::class, 'index'])->name('corte.index');
    
    //Actualizar
    Route::get('/corte-mensual/dia/{fecha}', [CorteController::class, 'getDetalleDia'])->name('corte.dia');

   // --- MÓDULO DE PRODUCTOS: PIZZAS ---
    Route::get('/productos/pizzas', [PizzaController::class, 'index'])->name('pizzas.index');
    Route::get('/productos/pizzas/crear', [PizzaController::class, 'create'])->name('pizzas.create');
    Route::post('/productos/pizzas', [PizzaController::class, 'store'])->name('pizzas.store');
    Route::get('/productos/pizzas/{id}/editar', [PizzaController::class, 'edit'])->name('pizzas.edit');
    Route::put('/productos/pizzas/{id}', [PizzaController::class, 'update'])->name('pizzas.update');
    Route::delete('/productos/pizzas/{id}', [PizzaController::class, 'destroy'])->name('pizzas.destroy');

    // --- MÓDULO DE PRODUCTOS: ALITAS ---
    Route::get('/productos/alitas', [AlitasController::class, 'index'])->name('alitas.index');
    Route::get('/productos/alitas/crear', [AlitasController::class, 'create'])->name('alitas.create');
    Route::post('/productos/alitas', [AlitasController::class, 'store'])->name('alitas.store');
    Route::get('/productos/alitas/{id}/editar', [AlitasController::class, 'edit'])->name('alitas.edit');
    Route::put('/productos/alitas/{id}', [AlitasController::class, 'update'])->name('alitas.update');
    Route::delete('/productos/alitas/{id}', [AlitasController::class, 'destroy'])->name('alitas.destroy');

    // --- MÓDULO DE PRODUCTOS: COSTILLAS ---
    Route::get('/productos/costillas', [CostillasController::class, 'index'])->name('costillas.index');
    Route::get('/productos/costillas/crear', [CostillasController::class, 'create'])->name('costillas.create');
    Route::post('/productos/costillas', [CostillasController::class, 'store'])->name('costillas.store');
    Route::get('/productos/costillas/{id}/editar', [CostillasController::class, 'edit'])->name('costillas.edit');
    Route::put('/productos/costillas/{id}', [CostillasController::class, 'update'])->name('costillas.update');
    Route::delete('/productos/costillas/{id}', [CostillasController::class, 'destroy'])->name('costillas.destroy');

    // --- MÓDULO DE PRODUCTOS: HAMBURGUESAS ---
    Route::get('/productos/hamburguesas', [HamburguesasController::class, 'index'])->name('hamburguesas.index');
    Route::get('/productos/hamburguesas/crear', [HamburguesasController::class, 'create'])->name('hamburguesas.create');
    Route::post('/productos/hamburguesas', [HamburguesasController::class, 'store'])->name('hamburguesas.store');
    Route::get('/productos/hamburguesas/{id}/editar', [HamburguesasController::class, 'edit'])->name('hamburguesas.edit');
    Route::put('/productos/hamburguesas/{id}', [HamburguesasController::class, 'update'])->name('hamburguesas.update');
    Route::delete('/productos/hamburguesas/{id}', [HamburguesasController::class, 'destroy'])->name('hamburguesas.destroy');

    // --- MÓDULO DE PRODUCTOS: MAGNO ---
    Route::get('/productos/magno', [MagnoController::class, 'index'])->name('magno.index');
    Route::get('/productos/magno/crear', [MagnoController::class, 'create'])->name('magno.create');
    Route::post('/productos/magno', [MagnoController::class, 'store'])->name('magno.store');
    Route::get('/productos/magno/{id}/editar', [MagnoController::class, 'edit'])->name('magno.edit');
    Route::put('/productos/magno/{id}', [MagnoController::class, 'update'])->name('magno.update');
    Route::delete('/productos/magno/{id}', [MagnoController::class, 'destroy'])->name('magno.destroy');
});