<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClientesController extends Controller
{
    public function index()
    {
        $clientes = DB::table('clientes')->orderBy('id_clie', 'desc')->get();
        
        $todasDirecciones = DB::table('direcciones')
            ->where('status', 1)
            ->get()
            ->groupBy('id_clie');

        return view('clientes.index', compact('clientes', 'todasDirecciones'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20'
        ]);

        $id_clie = DB::table('clientes')->insertGetId([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if ($request->filled('calle')) {
            DB::table('direcciones')->insert([
                'id_clie' => $id_clie,
                'calle' => $request->calle,
                'manzana' => $request->manzana,
                'lote' => $request->lote,
                'colonia' => $request->colonia,
                'referencia' => $request->referencia,
                'status' => 1
            ]);
        }

        return redirect()->route('clientes.index')->with('success', 'Cliente guardado exitosamente.');
    }

    public function edit($id)
    {
        $cliente = DB::table('clientes')->where('id_clie', $id)->first();
        $direcciones = DB::table('direcciones')->where('id_clie', $id)->where('status', 1)->get();
        
        return view('clientes.edit', compact('cliente', 'direcciones'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20'
        ]);
        
        DB::table('clientes')->where('id_clie', $id)->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'updated_at' => Carbon::now()
        ]);

        if ($request->has('direcciones')) {
            foreach ($request->direcciones as $id_dir => $dirData) {
                DB::table('direcciones')->where('id_dir', $id_dir)->update([
                    'calle' => $dirData['calle'],
                    'manzana' => $dirData['manzana'],
                    'lote' => $dirData['lote'],
                    'colonia' => $dirData['colonia'],
                    'referencia' => $dirData['referencia']
                ]);
            }
        }
        
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function storeDireccion(Request $request, $id)
    {
        DB::table('direcciones')->insert([
            'id_clie' => $id,
            'calle' => $request->calle,
            'manzana' => $request->manzana,
            'lote' => $request->lote,
            'colonia' => $request->colonia,
            'referencia' => $request->referencia,
            'status' => 1
        ]);
        return back()->with('success', 'Dirección agregada exitosamente.');
    }

    public function destroyDireccion($id)
    {
        DB::table('direcciones')->where('id_dir', $id)->update(['status' => 0]); 
        return back()->with('success', 'Dirección eliminada.');
    }

    // Desactivar cliente
    public function destroy($id)
    {
        DB::table('clientes')->where('id_clie', $id)->update(['status' => 0]); 
        return redirect()->route('clientes.index')->with('success', 'Cliente desactivado exitosamente');
    }

    // Activar cliente
    public function activar($id)
    {
        DB::table('clientes')->where('id_clie', $id)->update(['status' => 1]); 
        return redirect()->route('clientes.index')->with('success', 'Cliente activado exitosamente');
    }
}