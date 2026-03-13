<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SucursalesController extends Controller
{
    public function index()
    {
        // La tabla es singular 'Sucursal' (Confirmado por tu log de SQL)
        $sucursales = DB::table('Sucursal')->get();
        
        // OPCIÓN A: Si tus carpetas están en resources/views/sucursales/index.blade.php
        return view('sucursales.index', compact('sucursales'));
        
        // NOTA: Si te sigue dando error de "View not found", cambia la línea de arriba por:
        // return view('recursos.sucursales.index', compact('sucursales'));
    }

    public function create()
    {
        return view('sucursales.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string',
            'telefono' => 'required|string|max:20'
        ]);

        DB::table('Sucursal')->insert([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono
        ]);

        return redirect()->route('sucursales.index')->with('success', 'Sucursal añadida.');
    }

    public function edit($id)
    {
        $sucursal = DB::table('Sucursal')->where('id_suc', $id)->first();
        if (!$sucursal) abort(404);

        return view('sucursales.edit', compact('sucursal'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string',
            'telefono' => 'required|string|max:20'
        ]);
        
        DB::table('Sucursal')->where('id_suc', $id)->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono
        ]);
        
        return redirect()->route('sucursales.index')->with('success', 'Sucursal actualizada.');
    }

    public function destroy($id)
    {
        DB::table('Sucursal')->where('id_suc', $id)->delete();
        return redirect()->route('sucursales.index')->with('success', 'Sucursal eliminada.');
    }
}