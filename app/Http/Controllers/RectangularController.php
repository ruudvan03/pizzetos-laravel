<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RectangularController extends Controller
{
    public function index()
    {
        $rectangulares = DB::table('rectangular')
            ->join('especialidades', 'rectangular.id_esp', '=', 'especialidades.id_esp')
            ->join('categorias_prod', 'rectangular.id_cat', '=', 'categorias_prod.id_cat')
            ->select(
                'rectangular.id_rec', 
                'especialidades.nombre as especialidad', 
                'rectangular.precio', 
                'categorias_prod.descripcion as categoria'
            )
            ->get();

        return view('rectangular.index', compact('rectangulares'));
    }

    public function create()
    {
        $especialidades = DB::table('especialidades')->get();
        $categorias = DB::table('categorias_prod')->get();
        
        return view('rectangular.create', compact('especialidades', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_esp' => 'required|integer',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);

        DB::table('rectangular')->insert([
            'id_esp' => $request->id_esp,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);

        return redirect()->route('rectangular.index')->with('success', 'Pizza Rectangular añadida correctamente.');
    }

    public function edit($id)
    {
        $rectangular = DB::table('rectangular')->where('id_rec', $id)->first();
        $especialidades = DB::table('especialidades')->get();
        $categorias = DB::table('categorias_prod')->get();

        return view('rectangular.edit', compact('rectangular', 'especialidades', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_esp' => 'required|integer',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);
        
        DB::table('rectangular')->where('id_rec', $id)->update([
            'id_esp' => $request->id_esp,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);
        
        return redirect()->route('rectangular.index')->with('success', 'Pizza Rectangular actualizada correctamente.');
    }

    public function destroy($id)
    {
        DB::table('rectangular')->where('id_rec', $id)->delete();
        return redirect()->route('rectangular.index')->with('success', 'Pizza Rectangular eliminada correctamente.');
    }
}