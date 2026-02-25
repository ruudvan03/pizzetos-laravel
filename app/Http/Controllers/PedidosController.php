<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
    public function index()
    {
        $pedidosEspera = DB::table('venta')
            ->where('status', 0)
            ->orderBy('fecha_hora', 'asc')
            ->get();

        $pedidosPreparando = DB::table('venta')
            ->where('status', 1)
            ->orderBy('fecha_hora', 'asc')
            ->get();

        return view('ventas.pedidos', compact('pedidosEspera', 'pedidosPreparando'));
    }

    public function cambiarStatus(Request $request, $id)
    {
        $venta = DB::table('venta')->where('id_venta', $id)->first();
        
        if ($venta) {
            $nuevoStatus = $venta->status == 0 ? 1 : 2;
            
            DB::table('venta')->where('id_venta', $id)->update([
                'status' => $nuevoStatus
            ]);
        }

        return redirect()->route('ventas.pedidos')->with('success', 'Estado del pedido actualizado.');
    }
}