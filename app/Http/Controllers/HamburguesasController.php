<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HamburguesasController extends Controller
{
    public function index()
    {
        $hamburguesas = DB::table('hamburguesas')
            ->join('categorias_prod', 'hamburguesas.id_cat', '=', 'categorias_prod.id_cat')
            ->select('hamburguesas.id_hamb', 'hamburguesas.paquete', 'hamburguesas.precio', 'categorias_prod.descripcion as categoria')
            ->get();

        return view('hamburguesas.index', compact('hamburguesas'));
    }

    public function create()
    {
        $categorias = DB::table('categorias_prod')->get();
        return view('hamburguesas.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paquete' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);

        DB::table('hamburguesas')->insert([
            'paquete' => $request->paquete,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);

        return redirect()->route('hamburguesas.index')->with('success', 'Hamburguesa añadida correctamente.');
    }

    public function edit($id)
    {
        $hamburguesa = DB::table('hamburguesas')->where('id_hamb', $id)->first();
        $categorias = DB::table('categorias_prod')->get();

        return view('hamburguesas.edit', compact('hamburguesa', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'paquete' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);
        
        DB::table('hamburguesas')->where('id_hamb', $id)->update([
            'paquete' => $request->paquete,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);
        
        return redirect()->route('hamburguesas.index')->with('success', 'Hamburguesa actualizada correctamente.');
    }

    public function destroy($id)
    {
        DB::table('hamburguesas')->where('id_hamb', $id)->delete();
        return redirect()->route('hamburguesas.index')->with('success', 'Hamburguesa eliminada correctamente.');
    }
}