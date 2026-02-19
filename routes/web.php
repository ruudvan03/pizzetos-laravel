<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmpleadoController;

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


// --- RUTAS PROTEGIDAS (Requieren inicio de sesión) ---
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- MÓDULO DE EMPLEADOS ---
    
    // 1. Listado principal (Index)
    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');

    // 2. Formulario de creación (Create) - IMPORTANTE: Debe ir antes de rutas con {id}
    Route::get('/empleados/crear', [EmpleadoController::class, 'create'])->name('empleados.create');

    // 3. Guardar en base de datos (Store)
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');

    // 1. Mostrar formulario de edición
    Route::get('/empleados/{id}/editar', [EmpleadoController::class, 'edit'])->name('empleados.edit');

    // 2. Actualizar datos en BD (Put)
    Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');

    // 3. Eliminar registro (Delete)
    Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

});