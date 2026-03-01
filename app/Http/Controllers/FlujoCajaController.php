<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class FlujoCajaController extends Controller
{
    public function index()
    {
        $id_sucursal = Auth::check() ? (Auth::user()->id_suc ?? 1) : 1;
        
        $cajaAbierta = DB::table('caja')
            ->leftJoin('empleados', 'caja.id_emp', '=', 'empleados.id_emp')
            ->select('caja.*', 'empleados.nickName as cajero_nombre')
            ->where('caja.status', 1)
            ->where('caja.id_suc', $id_sucursal)
            ->first();

        $stats = ['num_ventas' => 0, 'total_gastos' => 0, 'venta_total' => 0, 'efectivo' => 0, 'tarjeta' => 0, 'transferencia' => 0];
        $ventas_detalle = collect(); 
        $gastos_detalle = collect();

        if ($cajaAbierta) {
            $gastos_detalle = DB::table('gastos')->where('id_caja', $cajaAbierta->id_caja)->get();
            $stats['total_gastos'] = $gastos_detalle->sum('precio');

            $ventas_detalle = DB::table('venta')->where('id_caja', $cajaAbierta->id_caja)->get();
            $stats['num_ventas'] = $ventas_detalle->count();
            $stats['venta_total'] = $ventas_detalle->sum('total');

            $pagos = DB::table('pago')
                ->join('venta', 'pago.id_venta', '=', 'venta.id_venta')
                ->join('metodospago', 'pago.id_metpago', '=', 'metodospago.id_metpago')
                ->where('venta.id_caja', $cajaAbierta->id_caja)
                ->select('metodospago.metodo', DB::raw('SUM(pago.monto) as total_monto'))
                ->groupBy('metodospago.metodo')
                ->pluck('total_monto', 'metodo');

            $stats['efectivo'] = $pagos['Efectivo'] ?? 0;
            $stats['tarjeta'] = $pagos['Tarjeta'] ?? 0;
            $stats['transferencia'] = $pagos['Transferencia'] ?? 0;
        }

        return view('ventas.flujo_caja', compact('cajaAbierta', 'stats', 'ventas_detalle', 'gastos_detalle'));
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
        $request->validate([
            'monto_final' => 'required|numeric|min:0',
            'observaciones_cierre' => 'nullable|string'
        ]);

        DB::table('caja')->where('id_caja', $id)->update([
            'fecha_cierre' => Carbon::now(),
            'monto_final' => $request->monto_final,
            'observaciones_cierre' => $request->observaciones_cierre,
            'status' => 0
        ]);

        return redirect()->route('flujo.caja.index')
            ->with('success', 'Caja cerrada correctamente. ¡Excelente turno!')
            ->with('download_pdf', $id);
    }

    public function descargarPdf($id)
    {
        $caja = DB::table('caja')
            ->leftJoin('empleados', 'caja.id_emp', '=', 'empleados.id_emp')
            ->select('caja.*', 'empleados.nickName as cajero_nombre')
            ->where('id_caja', $id)->first();

        if(!$caja) abort(404);

        $gastos = DB::table('gastos')->where('id_caja', $id)->sum('precio');
        $ventas = DB::table('venta')->where('id_caja', $id)->get();
        
        $pagos = DB::table('pago')
            ->join('venta', 'pago.id_venta', '=', 'venta.id_venta')
            ->join('metodospago', 'pago.id_metpago', '=', 'metodospago.id_metpago')
            ->where('venta.id_caja', $id)
            ->select('metodospago.metodo', DB::raw('SUM(pago.monto) as total_monto'))
            ->groupBy('metodospago.metodo')->pluck('total_monto', 'metodo');

        $stats = [
            'num_ventas' => $ventas->count(),
            'venta_total' => $ventas->sum('total'),
            'total_gastos' => $gastos,
            'efectivo' => $pagos['Efectivo'] ?? 0,
            'tarjeta' => $pagos['Tarjeta'] ?? 0,
            'transferencia' => $pagos['Transferencia'] ?? 0,
        ];

        $pdf = Pdf::loadView('ventas.pdf_caja', compact('caja', 'stats'));
        return $pdf->stream('Reporte_Caja_'.$id.'.pdf');
    }
}