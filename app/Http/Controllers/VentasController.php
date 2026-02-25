<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentasController extends Controller
{
    public function resume(Request $request)
    {
        $query = DB::table('venta')->orderBy('fecha_hora', 'desc');

        $filtroFecha = $request->input('fecha', 'hoy');
        $filtroEstado = $request->input('estado', 'todos');

        if ($filtroFecha == 'hoy') {
            $query->whereDate('fecha_hora', Carbon::today());
        } elseif ($filtroFecha == 'semana') {
            $query->whereBetween('fecha_hora', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filtroFecha == 'mes') {
            $query->whereMonth('fecha_hora', Carbon::now()->month)
            ->whereYear('fecha_hora', Carbon::now()->year);
        }

        if ($filtroEstado != 'todos') {
            $query->where('status', $filtroEstado);
        }

        $ventas = $query->get();

        return view('ventas.resume', compact('ventas', 'filtroFecha', 'filtroEstado'));
    }
}