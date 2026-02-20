<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CostillasController extends Controller
{
    public function index()
    {
        $costillas = DB::table('costillas')
            ->join('categorias_prod', 'costillas.id_cat', '=', 'categorias_prod.id_cat')
            ->select('costillas.id_cos', 'costillas.orden', 'costillas.precio', 'categorias_prod.descripcion as categoria')
            ->get();

        return view('costillas.index', compact('costillas'));
    }

    public function create()
    {
        $categorias = DB::table('categorias_prod')->get();
        return view('costillas.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);

        DB::table('costillas')->insert([
            'orden' => $request->orden,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);

        return redirect()->route('costillas.index')->with('success', 'Costillas añadidas correctamente.');
    }

    public function edit($id)
    {
        $costilla = DB::table('costillas')->where('id_cos', $id)->first();
        $categorias = DB::table('categorias_prod')->get();

        return view('costillas.edit', compact('costilla', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'orden' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);
        
        DB::table('costillas')->where('id_cos', $id)->update([
            'orden' => $request->orden,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);
        
        return redirect()->route('costillas.index')->with('success', 'Costillas actualizadas correctamente.');
    }

    public function destroy($id)
    {
        DB::table('costillas')->where('id_cos', $id)->delete();
        return redirect()->route('costillas.index')->with('success', 'Costillas eliminadas correctamente.');
    }
}