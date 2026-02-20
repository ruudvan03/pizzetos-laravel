<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PizzaController extends Controller
{
    // Mostrar la lista
    public function index()
    {
        $pizzas = DB::table('pizzas')
            ->join('especialidades', 'pizzas.id_esp', '=', 'especialidades.id_esp')
            ->join('tamanos_pizza', 'pizzas.id_tamano', '=', 'tamanos_pizza.id_tamañop')
            ->join('categorias_prod', 'pizzas.id_cat', '=', 'categorias_prod.id_cat')
            ->select('pizzas.id_pizza', 'especialidades.nombre as especialidad', 'tamanos_pizza.tamano as tamano', 'categorias_prod.descripcion as categoria')
            ->get();

        return view('pizzas.index', compact('pizzas'));
    }

    // Crear
    public function create()
    {
        $especialidades = DB::table('especialidades')->get();
        $tamanos = DB::table('tamanos_pizza')->get();
        $categorias = DB::table('categorias_prod')->get();
        return view('pizzas.create', compact('especialidades', 'tamanos', 'categorias'));
    }

    // Guardar
    public function store(Request $request)
    {
        $request->validate(['id_esp' => 'required|integer', 'id_tamano' => 'required|integer', 'id_cat' => 'required|integer']);
        DB::table('pizzas')->insert(['id_esp' => $request->id_esp, 'id_tamano' => $request->id_tamano, 'id_cat' => $request->id_cat]);
        return redirect()->route('pizzas.index')->with('success', 'Pizza añadida correctamente.');
    }

    //Editar
    public function edit($id)
    {
        $pizza = DB::table('pizzas')->where('id_pizza', $id)->first();
        $especialidades = DB::table('especialidades')->get();
        $tamanos = DB::table('tamanos_pizza')->get();
        $categorias = DB::table('categorias_prod')->get();

        return view('pizzas.edit', compact('pizza', 'especialidades', 'tamanos', 'categorias'));
    }

    // Guardar
    public function update(Request $request, $id)
    {
        $request->validate(['id_esp' => 'required|integer', 'id_tamano' => 'required|integer', 'id_cat' => 'required|integer']);
        
        DB::table('pizzas')->where('id_pizza', $id)->update([
            'id_esp' => $request->id_esp,
            'id_tamano' => $request->id_tamano,
            'id_cat' => $request->id_cat
        ]);
        
        return redirect()->route('pizzas.index')->with('success', 'Pizza actualizada correctamente.');
    }

    // Eliminar
    public function destroy($id)
    {
        DB::table('pizzas')->where('id_pizza', $id)->delete();
        return redirect()->route('pizzas.index')->with('success', 'Pizza eliminada correctamente.');
    }
}