<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CargosController extends Controller
{
    public function index()
    {
        $cargos = DB::table('cargos')
            ->leftJoin('permisos', 'cargos.id_ca', '=', 'permisos.id_cargo')
            ->select('cargos.id_ca', 'cargos.nombre', 'permisos.*')
            ->get();
            
        return view('cargos.index', compact('cargos'));
    }

    public function create()
    {
        return view('cargos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $id_ca = DB::table('cargos')->insertGetId([
            'nombre' => $request->nombre,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('permisos')->insert([
            'id_cargo' => $id_ca,
            'crear_producto' => $request->has('crear_producto') ? 1 : 0,
            'modificar_producto' => $request->has('modificar_producto') ? 1 : 0,
            'eliminar_producto' => $request->has('eliminar_producto') ? 1 : 0,
            'ver_producto' => $request->has('ver_producto') ? 1 : 0,
            
            'crear_empleado' => $request->has('crear_empleado') ? 1 : 0,
            'modificar_empleado' => $request->has('modificar_empleado') ? 1 : 0,
            'eliminar_empleado' => $request->has('eliminar_empleado') ? 1 : 0,
            'ver_empleado' => $request->has('ver_empleado') ? 1 : 0,
            
            'crear_venta' => $request->has('crear_venta') ? 1 : 0,
            'modificar_venta' => $request->has('modificar_venta') ? 1 : 0,
            'eliminar_venta' => $request->has('eliminar_venta') ? 1 : 0,
            'ver_venta' => $request->has('ver_venta') ? 1 : 0,
            
            'crear_recurso' => $request->has('crear_recurso') ? 1 : 0,
            'modificar_recurso' => $request->has('modificar_recurso') ? 1 : 0,
            'eliminar_recurso' => $request->has('eliminar_recurso') ? 1 : 0,
            'ver_recurso' => $request->has('ver_recurso') ? 1 : 0,
        ]);

        return redirect()->route('cargos.index')->with('success', 'Cargo añadido correctamente.');
    }

    public function edit($id)
    {
        $cargo = DB::table('cargos')
            ->leftJoin('permisos', 'cargos.id_ca', '=', 'permisos.id_cargo')
            ->select('cargos.id_ca', 'cargos.nombre', 'permisos.*')
            ->where('cargos.id_ca', $id)
            ->first();
            
        return view('cargos.edit', compact('cargo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
        
        DB::table('cargos')->where('id_ca', $id)->update([
            'nombre' => $request->nombre,
            'updated_at' => Carbon::now()
        ]);
        
        $permisosData = [
            'crear_producto' => $request->has('crear_producto') ? 1 : 0,
            'modificar_producto' => $request->has('modificar_producto') ? 1 : 0,
            'eliminar_producto' => $request->has('eliminar_producto') ? 1 : 0,
            'ver_producto' => $request->has('ver_producto') ? 1 : 0,
            
            'crear_empleado' => $request->has('crear_empleado') ? 1 : 0,
            'modificar_empleado' => $request->has('modificar_empleado') ? 1 : 0,
            'eliminar_empleado' => $request->has('eliminar_empleado') ? 1 : 0,
            'ver_empleado' => $request->has('ver_empleado') ? 1 : 0,
            
            'crear_venta' => $request->has('crear_venta') ? 1 : 0,
            'modificar_venta' => $request->has('modificar_venta') ? 1 : 0,
            'eliminar_venta' => $request->has('eliminar_venta') ? 1 : 0,
            'ver_venta' => $request->has('ver_venta') ? 1 : 0,
            
            'crear_recurso' => $request->has('crear_recurso') ? 1 : 0,
            'modificar_recurso' => $request->has('modificar_recurso') ? 1 : 0,
            'eliminar_recurso' => $request->has('eliminar_recurso') ? 1 : 0,
            'ver_recurso' => $request->has('ver_recurso') ? 1 : 0,
        ];

        $existePermiso = DB::table('permisos')->where('id_cargo', $id)->exists();
        if ($existePermiso) {
            DB::table('permisos')->where('id_cargo', $id)->update($permisosData);
        } else {
            $permisosData['id_cargo'] = $id;
            DB::table('permisos')->insert($permisosData);
        }
        
        return redirect()->route('cargos.index')->with('success', 'Cargo actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('permisos')->where('id_cargo', $id)->delete();
        DB::table('cargos')->where('id_ca', $id)->delete();
        
        return redirect()->route('cargos.index')->with('success', 'Cargo eliminado correctamente.');
    }
}