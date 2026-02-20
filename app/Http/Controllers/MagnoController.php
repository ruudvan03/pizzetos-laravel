<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MagnoController extends Controller
{
    public function index()
    {
        $magnos = DB::table('magno')
            ->join('especialidades', 'magno.id_especialidad', '=', 'especialidades.id_esp')
            ->join('refrescos', 'magno.id_refresco', '=', 'refrescos.id_refresco')
            ->select(
                'magno.id_magno', 
                'especialidades.nombre as especialidad', 
                'refrescos.nombre as refresco', 
                'magno.precio'
            )
            ->get();

        return view('magno.index', compact('magnos'));
    }

    public function create()
    {
        $especialidades = DB::table('especialidades')->get();
        $refrescos = DB::table('refrescos')->get();
        
        return view('magno.create', compact('especialidades', 'refrescos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_especialidad' => 'required|integer',
            'id_refresco' => 'required|integer',
            'precio' => 'required|numeric'
        ]);

        DB::table('magno')->insert([
            'id_especialidad' => $request->id_especialidad,
            'id_refresco' => $request->id_refresco,
            'precio' => $request->precio
        ]);

        return redirect()->route('magno.index')->with('success', 'Producto Magno añadido correctamente.');
    }

    public function edit($id)
    {
        $magno = DB::table('magno')->where('id_magno', $id)->first();
        $especialidades = DB::table('especialidades')->get();
        $refrescos = DB::table('refrescos')->get();

        return view('magno.edit', compact('magno', 'especialidades', 'refrescos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_especialidad' => 'required|integer',
            'id_refresco' => 'required|integer',
            'precio' => 'required|numeric'
        ]);
        
        DB::table('magno')->where('id_magno', $id)->update([
            'id_especialidad' => $request->id_especialidad,
            'id_refresco' => $request->id_refresco,
            'precio' => $request->precio
        ]);
        
        return redirect()->route('magno.index')->with('success', 'Producto Magno actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('magno')->where('id_magno', $id)->delete();
        return redirect()->route('magno.index')->with('success', 'Producto Magno eliminado correctamente.');
    }
}