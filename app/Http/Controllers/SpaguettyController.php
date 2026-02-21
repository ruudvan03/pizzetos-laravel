<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpaguettyController extends Controller
{
    public function index()
    {
        $spaguettis = DB::table('spaguetty')
            ->join('categorias_prod', 'spaguetty.id_cat', '=', 'categorias_prod.id_cat')
            ->select('spaguetty.id_spag', 'spaguetty.orden', 'spaguetty.precio', 'categorias_prod.descripcion as categoria')
            ->get();

        return view('spaguetty.index', compact('spaguettis'));
    }

    public function create()
    {
        $categorias = DB::table('categorias_prod')->get();
        return view('spaguetty.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);

        DB::table('spaguetty')->insert([
            'orden' => $request->orden,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);

        return redirect()->route('spaguetty.index')->with('success', 'Spaguetty añadido correctamente.');
    }

    public function edit($id)
    {
        $spaguetty = DB::table('spaguetty')->where('id_spag', $id)->first();
        $categorias = DB::table('categorias_prod')->get();

        return view('spaguetty.edit', compact('spaguetty', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'orden' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'id_cat' => 'required|integer'
        ]);
        
        DB::table('spaguetty')->where('id_spag', $id)->update([
            'orden' => $request->orden,
            'precio' => $request->precio,
            'id_cat' => $request->id_cat
        ]);
        
        return redirect()->route('spaguetty.index')->with('success', 'Spaguetty actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('spaguetty')->where('id_spag', $id)->delete();
        return redirect()->route('spaguetty.index')->with('success', 'Spaguetty eliminado correctamente.');
    }
}