<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FlujoCajaController extends Controller
{
    public function index()
    {
        
        $id_sucursal = Auth::check() ? (Auth::user()->id_suc ?? 1) : 1;
        
        $cajaAbierta = DB::table('caja')
            ->where('status', 1)
            ->where('id_suc', $id_sucursal)
            ->first();

        return view('ventas.flujo_caja', compact('cajaAbierta'));
    }

    public function abrirCaja(Request $request)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:255'
        ]);

        $id_emp = Auth::check() ? (Auth::user()->id_emp ?? 1) : 1;
        $id_suc = Auth::check() ? (Auth::user()->id_suc ?? 1) : 1;

        DB::table('caja')->insert([
            'id_suc' => $id_suc,
            'id_emp' => $id_emp,
            'fecha_apertura' => Carbon::now(),
            'monto_inicial' => $request->monto_inicial,
            'status' => 1,
            'observaciones_apertura' => $request->observaciones ?? ''
        ]);

        return redirect()->route('flujo.caja.index')->with('success', 'Caja abierta exitosamente.');
    }

    public function cerrarCaja(Request $request, $id)
    {
        DB::table('caja')->where('id_caja', $id)->update([
            'fecha_cierre' => Carbon::now(),
            'status' => 0
        ]);

        return redirect()->route('flujo.caja.index')->with('success', 'Caja cerrada exitosamente.');
    }
}