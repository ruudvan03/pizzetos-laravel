<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarraController extends Controller
{
    public function index()
    {
        $barras = DB::table('barra')
            ->join('especialidades', 'barra.id_especialidad', '=', 'especialidades.id_esp')
            ->join('categorias_prod', 'barra.id_cat', '=', 'categorias_prod.id_cat')
            ->select(
                'barra.id_barr', 
                'especialidades.nombre as especialidad', 
                'barra.precio', 
                'categorias_prod.descripcion as categoria'
            )
            ->get();

        return view('barra.index', compact('barras'));
    }

    public function create()
    {
        $especialidades = DB::table('especialidades')->get();
        $categorias = DB::table('categorias_prod')->get();
        
        return view('barra.create', compact('especialidades', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_especialidad' => 'required|integer',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);

        DB::table('barra')->insert([
            'id_especialidad' => $request->id_especialidad,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);

        return redirect()->route('barra.index')->with('success', 'Producto de Barra añadido correctamente.');
    }

    public function edit($id)
    {
        $barra = DB::table('barra')->where('id_barr', $id)->first();
        $especialidades = DB::table('especialidades')->get();
        $categorias = DB::table('categorias_prod')->get();

        return view('barra.edit', compact('barra', 'especialidades', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_especialidad' => 'required|integer',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);
        
        DB::table('barra')->where('id_barr', $id)->update([
            'id_especialidad' => $request->id_especialidad,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);
        
        return redirect()->route('barra.index')->with('success', 'Producto de Barra actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('barra')->where('id_barr', $id)->delete();
        return redirect()->route('barra.index')->with('success', 'Producto de Barra eliminado correctamente.');
    }
}