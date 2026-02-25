<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GastosController extends Controller
{
    public function index()
    {
        $id_suc = Auth::check() ? (Auth::user()->id_suc ?? 1) : 1;
        
        $cajaAbierta = DB::table('caja')
            ->where('status', 1)
            ->where('id_suc', $id_suc)
            ->first();

        $gastos = collect();
        
        if ($cajaAbierta) {
            $gastos = DB::table('gastos')
                ->where('id_caja', $cajaAbierta->id_caja)
                ->orderBy('fecha', 'desc')
                ->get();
        }

        return view('gastos.index', compact('cajaAbierta', 'gastos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0.1'
        ]);

        $id_suc = Auth::check() ? (Auth::user()->id_suc ?? 1) : 1;
        $cajaAbierta = DB::table('caja')->where('status', 1)->where('id_suc', $id_suc)->first();

        if (!$cajaAbierta) {
            return back()->with('error', 'Debes abrir la caja en Flujo de Caja antes de registrar gastos.');
        }

        DB::table('gastos')->insert([
            'id_suc' => $id_suc,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'fecha' => Carbon::now(),
            'evaluado' => 0,
            'id_caja' => $cajaAbierta->id_caja
        ]);

        return back()->with('success', 'Gasto registrado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('gastos')->where('id_gastos', $id)->delete();
        return back()->with('success', 'Gasto eliminado.');
    }
}