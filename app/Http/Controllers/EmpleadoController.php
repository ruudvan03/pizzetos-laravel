<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Cargo;
use App\Models\Sucursal;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::all();
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $cargos = Cargo::all(); 
        $sucursales = Sucursal::all();
        return view('empleados.create', compact('cargos', 'sucursales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'nickName' => 'required|string|unique:empleados,nickName',
            'telefono' => 'required',
            'id_ca'    => 'required', 
            'id_suc'   => 'required',
            'password' => 'required|string|min:6',
        ]);
        
        $empleado = new Empleado($request->all());
        $empleado->status = 1; 
        
        $empleado->password = bcrypt($request->password);
        
        $empleado->save();

        return redirect()->route('empleados.index')->with('success', '¡Empleado registrado correctamente!');
    }

    // 1. EDITAR
    public function edit($id)
    {
        $empleado = Empleado::where('id_emp', $id)->firstOrFail();
        
        $cargos = Cargo::all();
        $sucursales = Sucursal::all();

        return view('empleados.edit', compact('empleado', 'cargos', 'sucursales'));
    }

    // 2. ACTUALIZAR
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'telefono' => 'required',
            'id_ca'    => 'required',
            'id_suc'   => 'required',
            'nickName' => 'required|string|unique:empleados,nickName,' . $id . ',id_emp',
            'password' => 'nullable|string|min:6',
        ]);

        $empleado = Empleado::where('id_emp', $id)->firstOrFail();
        
        $datosParaActualizar = $request->except('password');

        if ($request->filled('password')) {
            $datosParaActualizar['password'] = bcrypt($request->password);
        }

        $empleado->update($datosParaActualizar);

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado con éxito');
    }

    // 3. ELIMINAR
    public function destroy($id)
    {
        $empleado = Empleado::where('id_emp', $id)->firstOrFail();
        $empleado->delete();

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado');
    }

    // 4. CAMBIAR ESTADO 
    public function toggleStatus($id)
    {
        $empleado = Empleado::where('id_emp', $id)->firstOrFail();
        
        $empleado->status = $empleado->status == 1 ? 0 : 1;
        $empleado->save();

        $mensaje = $empleado->status == 1 ? 'Empleado activado' : 'Empleado desactivado';
        
        return redirect()->route('empleados.index')->with('success', $mensaje);
    }
}