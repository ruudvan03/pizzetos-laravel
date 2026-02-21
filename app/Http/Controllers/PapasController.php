<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PapasController extends Controller
{
    public function index()
    {
        // Cambiamos 'papas' por 'orden_de_papas'
        $papas = DB::table('orden_de_papas')
            ->join('categorias_prod', 'orden_de_papas.id_cat', '=', 'categorias_prod.id_cat')
            ->select('orden_de_papas.id_papa', 'orden_de_papas.orden', 'orden_de_papas.precio', 'categorias_prod.descripcion as categoria')
            ->get();

        return view('papas.index', compact('papas'));
    }

    public function create()
    {
        $categorias = DB::table('categorias_prod')->get();
        return view('papas.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);

        DB::table('orden_de_papas')->insert([
            'orden' => $request->orden,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);

        return redirect()->route('papas.index')->with('success', 'Papas añadidas correctamente.');
    }

    public function edit($id)
    {
        $papa = DB::table('orden_de_papas')->where('id_papa', $id)->first();
        $categorias = DB::table('categorias_prod')->get();

        return view('papas.edit', compact('papa', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'orden' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);
        
        DB::table('orden_de_papas')->where('id_papa', $id)->update([
            'orden' => $request->orden,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);
        
        return redirect()->route('papas.index')->with('success', 'Papas actualizadas correctamente.');
    }

    public function destroy($id)
    {
        DB::table('orden_de_papas')->where('id_papa', $id)->delete();
        return redirect()->route('papas.index')->with('success', 'Papas eliminadas correctamente.');
    }
}