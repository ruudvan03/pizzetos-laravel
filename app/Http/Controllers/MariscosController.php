<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MariscosController extends Controller
{
    public function index()
    {
        // Unimos con categorias_prod y tamanos_pizza
        $mariscos = DB::table('pizzas_mariscos')
            ->join('categorias_prod', 'pizzas_mariscos.id_cat', '=', 'categorias_prod.id_cat')
            ->join('tamanos_pizza', 'pizzas_mariscos.id_tamañop', '=', 'tamanos_pizza.id_tamañop')
            ->select(
                'pizzas_mariscos.id_maris', 
                'pizzas_mariscos.nombre', 
                'pizzas_mariscos.descripcion', 
                'tamanos_pizza.tamano', 
                'categorias_prod.descripcion as categoria'
            )
            ->get();

        return view('mariscos.index', compact('mariscos'));
    }

    public function create()
    {
        // Traemos categorías y tamaños para los select
        $categorias = DB::table('categorias_prod')->get();
        $tamanos = DB::table('tamanos_pizza')->get();
        
        return view('mariscos.create', compact('categorias', 'tamanos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'id_tamañop' => 'required|integer',
            'id_cat' => 'required|integer'
        ]);

        DB::table('pizzas_mariscos')->insert([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'id_tamañop' => $request->id_tamañop,
            'id_cat' => $request->id_cat
        ]);

        return redirect()->route('mariscos.index')->with('success', 'Pizza de Mariscos añadida correctamente.');
    }

    public function edit($id)
    {
        $marisco = DB::table('pizzas_mariscos')->where('id_maris', $id)->first();
        $categorias = DB::table('categorias_prod')->get();
        $tamanos = DB::table('tamanos_pizza')->get();

        return view('mariscos.edit', compact('marisco', 'categorias', 'tamanos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'id_tamañop' => 'required|integer',
            'id_cat' => 'required|integer'
        ]);
        
        DB::table('pizzas_mariscos')->where('id_maris', $id)->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'id_tamañop' => $request->id_tamañop,
            'id_cat' => $request->id_cat
        ]);
        
        return redirect()->route('mariscos.index')->with('success', 'Pizza de Mariscos actualizada correctamente.');
    }

    public function destroy($id)
    {
        DB::table('pizzas_mariscos')->where('id_maris', $id)->delete();
        return redirect()->route('mariscos.index')->with('success', 'Pizza de Mariscos eliminada correctamente.');
    }
}