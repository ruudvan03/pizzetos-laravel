<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlitasController extends Controller
{
    // Mostrar la lista
    public function index()
    {
        $alitas = DB::table('alitas')
            ->join('categorias_prod', 'alitas.id_cat', '=', 'categorias_prod.id_cat')
            ->select('alitas.id_alis', 'alitas.orden', 'alitas.precio', 'categorias_prod.descripcion as categoria')
            ->get();

        return view('alitas.index', compact('alitas'));
    }

    public function create()
    {
        $categorias = DB::table('categorias_prod')->get();
        return view('alitas.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);

        DB::table('alitas')->insert([
            'orden' => $request->orden,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);

        return redirect()->route('alitas.index')->with('success', 'Alitas añadidas correctamente.');
    }

    public function edit($id)
    {
        $alita = DB::table('alitas')->where('id_alis', $id)->first();
        $categorias = DB::table('categorias_prod')->get();

        return view('alitas.edit', compact('alita', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'orden' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);
        
        DB::table('alitas')->where('id_alis', $id)->update([
            'orden' => $request->orden,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);
        
        return redirect()->route('alitas.index')->with('success', 'Alitas actualizadas correctamente.');
    }

    public function destroy($id)
    {
        DB::table('alitas')->where('id_alis', $id)->delete();
        return redirect()->route('alitas.index')->with('success', 'Alitas eliminadas correctamente.');
    }
}