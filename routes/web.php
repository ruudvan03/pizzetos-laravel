<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmpleadoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

// --- RUTAS DE AUTENTICACIÓN ---
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    
    // Dashboard Principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- MÓDULO DE EMPLEADOS ---
    
    // 1. Listado principal (Index)
    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');

    // 2.Crear
    Route::get('/empleados/crear', [EmpleadoController::class, 'create'])->name('empleados.create');

    // 3. Guardar
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');

    // 4. Mostrar formulario de edición
    Route::get('/empleados/{id}/editar', [EmpleadoController::class, 'edit'])->name('empleados.edit');

    // 5. Actualizar
    Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');

    // 6. Eliminar registro
    Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

    // 7. Cambiar Estado Activo/Inactivo
    Route::patch('/empleados/{id}/estado', [EmpleadoController::class, 'toggleStatus'])->name('empleados.status');

});