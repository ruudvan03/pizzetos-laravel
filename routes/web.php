<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmpleadoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirección inicial: Si está logueado al dashboard, si no al login
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
    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
    // Aquí puedes ir agregando las demás (create, store, edit, update, etc.) conforme las necesites


    // --- PRÓXIMOS MÓDULOS (Espacios reservados) ---
    
    // Módulo de Clientes
    // Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');

    // Módulo de Productos (Pizzas, Alitas, Hamburguesas, etc.)
    // Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');

    // Módulo de Punto de Venta (POS)
    // Route::get('/pos', [VentaController::class, 'index'])->name('pos.index');

    // Módulo de Caja
    // Route::get('/caja', [CajaController::class, 'index'])->name('caja.index');

});