<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefrescosController extends Controller
{
    public function index()
    {
        $refrescos = DB::table('refrescos')
            ->join('tamanos_refrescos', 'refrescos.id_tamano', '=', 'tamanos_refrescos.id_tamano')
            ->join('categorias_prod', 'refrescos.id_cat', '=', 'categorias_prod.id_cat')
            ->select(
                'refrescos.id_refresco', 
                'refrescos.nombre', 
                'tamanos_refrescos.tamano', 
                'tamanos_refrescos.precio', 
                'categorias_prod.descripcion as categoria'
            )
            ->get();

        return view('refrescos.index', compact('refrescos'));
    }

    public function create()
    {
        $tamanos = DB::table('tamanos_refrescos')->get();
        $categorias = DB::table('categorias_prod')->get();
        
        return view('refrescos.create', compact('tamanos', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_tamano' => 'required|integer',
            'id_cat' => 'required|integer'
        ]);

        DB::table('refrescos')->insert([
            'nombre' => $request->nombre,
            'id_tamano' => $request->id_tamano,
            'id_cat' => $request->id_cat
        ]);

        return redirect()->route('refrescos.index')->with('success', 'Refresco añadido correctamente.');
    }

    public function edit($id)
    {
        $refresco = DB::table('refrescos')->where('id_refresco', $id)->first();
        $tamanos = DB::table('tamanos_refrescos')->get();
        $categorias = DB::table('categorias_prod')->get();

        return view('refrescos.edit', compact('refresco', 'tamanos', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_tamano' => 'required|integer',
            'id_cat' => 'required|integer'
        ]);
        
        DB::table('refrescos')->where('id_refresco', $id)->update([
            'nombre' => $request->nombre,
            'id_tamano' => $request->id_tamano,
            'id_cat' => $request->id_cat
        ]);
        
        return redirect()->route('refrescos.index')->with('success', 'Refresco actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('refrescos')->where('id_refresco', $id)->delete();
        return redirect()->route('refrescos.index')->with('success', 'Refresco eliminado correctamente.');
    }
}