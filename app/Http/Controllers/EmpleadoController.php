<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        // Traemos empleados con sus relaciones para no hacer muchas consultas
        $empleados = User::with(['cargo', 'sucursal'])->get();
        return view('empleados.index', compact('empleados'));
    }
}