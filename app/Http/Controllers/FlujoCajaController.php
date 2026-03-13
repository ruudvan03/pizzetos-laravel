<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class FlujoCajaController extends Controller
{
    /**
     * Muestra el panel de control de caja con nombres de cliente claros.
     */
    public function index()
    {
        $id_sucursal = 1;
        
        $cajaAbierta = DB::table('Caja')
            ->leftJoin('Empleados', 'Caja.id_emp', '=', 'Empleados.id_emp')
            ->select('Caja.*', 'Empleados.nickName as cajero_nombre')
            ->where('Caja.status', 1)
            ->where('Caja.id_suc', $id_sucursal)
            ->first();

        if (!$cajaAbierta) {
            return view('Ventas.flujo_caja', ['cajaAbierta' => null]);
        }

        // 1. GASTOS DETALLADOS
        try {
            $gastos_detalle = DB::table('Gastos')
                ->leftJoin('Empleados', 'Gastos.id_emp', '=', 'Empleados.id_emp')
                ->where('id_caja', $cajaAbierta->id_caja)
                ->select('Gastos.*', 'Empleados.nickName as responsable')
                ->get();
        } catch (\Exception $e) {
            $gastos_detalle = DB::table('Gastos')
                ->where('id_caja', $cajaAbierta->id_caja)
                ->select('Gastos.*', DB::raw("'N/A' as responsable"))
                ->get();
        }
        $total_gastos = $gastos_detalle->sum('precio');

        // 2. VENTAS CON LÓGICA DE NOMBRES PARA LA VISTA
        $ventas_detalle = DB::table('Venta')
            ->leftJoin('Pago', 'Venta.id_venta', '=', 'Pago.id_venta')
            ->leftJoin('MetodosPago', 'Pago.id_metpago', '=', 'MetodosPago.id_metpago')
            ->where('Venta.id_caja', $cajaAbierta->id_caja)
            ->select(
                'Venta.id_venta', 'Venta.fecha_hora', 'Venta.total', 'Venta.status', 'Venta.mesa', 'Venta.tipo_servicio',
                // Lógica: 1=Mesa, 2=Para Llevar, 3=Domicilio
                DB::raw("CASE 
                    WHEN Venta.tipo_servicio = 2 THEN 'PARA LLEVAR'
                    WHEN Venta.tipo_servicio = 1 THEN CONCAT('MESA ', COALESCE(Venta.mesa, ''), ' - ', COALESCE(Venta.nombreClie, 'CLIENTE'))
                    ELSE COALESCE(Venta.nombreClie, 'DOMICILIO')
                END as nombre_cliente_formateado"),
                DB::raw("GROUP_CONCAT(MetodosPago.metodo SEPARATOR ', ') as metodos_pago"),
                DB::raw("GROUP_CONCAT(COALESCE(Pago.referencia, 'S/R') SEPARATOR ' / ') as referencias"),
                DB::raw("GROUP_CONCAT(CONCAT(MetodosPago.metodo, ': $', Pago.monto) SEPARATOR ' + ') as montos_detalle")
            )
            ->groupBy('Venta.id_venta', 'Venta.fecha_hora', 'Venta.total', 'Venta.status', 'Venta.nombreClie', 'Venta.mesa', 'Venta.tipo_servicio')
            ->orderBy('Venta.id_venta', 'desc')
            ->get();

        // 3. TOTALES POR MÉTODO
        $pagos = DB::table('Pago')
            ->join('Venta', 'Pago.id_venta', '=', 'Venta.id_venta')
            ->join('MetodosPago', 'Pago.id_metpago', '=', 'MetodosPago.id_metpago')
            ->where('Venta.id_caja', $cajaAbierta->id_caja)
            ->where('Venta.status', '!=', 3) 
            ->select('MetodosPago.metodo', DB::raw('SUM(Pago.monto) as total_monto'))
            ->groupBy('MetodosPago.metodo')
            ->pluck('total_monto', 'metodo');

        $stats = [
            'num_ventas' => $ventas_detalle->where('status', '!=', 3)->count(),
            'venta_total' => $ventas_detalle->where('status', '!=', 3)->sum('total'),
            'total_gastos' => $total_gastos,
            'efectivo_ventas' => $pagos['Efectivo'] ?? 0,
            'tarjeta' => $pagos['Tarjeta'] ?? 0,
            'transferencia' => $pagos['Transferencia'] ?? 0,
        ];

        $stats['efectivo_real_en_sobre'] = $stats['efectivo_ventas'] - $stats['total_gastos'];

        return view('Ventas.flujo_caja', compact('cajaAbierta', 'stats', 'ventas_detalle', 'gastos_detalle'));
    }

    /**
     * Reporte PDF de Cierre con nombres corregidos.
     */
    public function descargarPdf($id)
    {
        $caja = DB::table('Caja')
            ->leftJoin('Empleados', 'Caja.id_emp', '=', 'Empleados.id_emp')
            ->select('Caja.*', 'Empleados.nickName as responsable_apertura')
            ->where('id_caja', $id)->first();

        if (!$caja) abort(404);

        try {
            $gastos = DB::table('Gastos')
                ->leftJoin('Empleados', 'Gastos.id_emp', '=', 'Empleados.id_emp')
                ->where('id_caja', $id)
                ->select('Gastos.*', 'Empleados.nickName as responsable')
                ->get();
        } catch (\Exception $e) {
            $gastos = DB::table('Gastos')
                ->where('id_caja', $id)
                ->select('Gastos.*', DB::raw("'N/A' as responsable"))
                ->get();
        }

        $ventas = DB::table('Venta')
            ->leftJoin('Pago', 'Venta.id_venta', '=', 'Pago.id_venta')
            ->leftJoin('MetodosPago', 'Pago.id_metpago', '=', 'MetodosPago.id_metpago')
            ->where('Venta.id_caja', $id)
            ->where('Venta.status', '!=', 3)
            ->select(
                'Venta.id_venta', 'Venta.total', 
                DB::raw("CASE 
                    WHEN Venta.tipo_servicio = 2 THEN 'PARA LLEVAR'
                    WHEN Venta.tipo_servicio = 1 THEN CONCAT('MESA ', COALESCE(Venta.mesa, ''), ' - ', COALESCE(Venta.nombreClie, 'CLIENTE'))
                    ELSE COALESCE(Venta.nombreClie, 'DOMICILIO')
                END as nombreClie"),
                DB::raw("GROUP_CONCAT(MetodosPago.metodo SEPARATOR ', ') as metodos"),
                DB::raw("GROUP_CONCAT(COALESCE(Pago.referencia, '-') SEPARATOR ' / ') as refs")
            )
            ->groupBy('Venta.id_venta', 'Venta.total', 'Venta.tipo_servicio', 'Venta.mesa', 'Venta.nombreClie')
            ->get();

        $pagos_pdf = DB::table('Pago')
            ->join('Venta', 'Pago.id_venta', '=', 'Venta.id_venta')
            ->join('MetodosPago', 'Pago.id_metpago', '=', 'MetodosPago.id_metpago')
            ->where('Venta.id_caja', $id)
            ->where('Venta.status', '!=', 3)
            ->select('MetodosPago.metodo', DB::raw('SUM(Pago.monto) as total'))
            ->groupBy('MetodosPago.metodo')->pluck('total', 'metodo');

        $stats = [
            'fondo' => $caja->monto_inicial,
            'num_ventas' => $ventas->count(),
            'venta_total' => $ventas->sum('total'),
            'total_gastos' => $gastos->sum('precio'),
            'gastos_total' => $gastos->sum('precio'),
            'efectivo' => $pagos_pdf['Efectivo'] ?? 0,
            'tarjeta' => $pagos_pdf['Tarjeta'] ?? 0,
            'transferencia' => $pagos_pdf['Transferencia'] ?? 0,
            'efectivo_esperado' => ($pagos_pdf['Efectivo'] ?? 0) - $gastos->sum('precio'),
            'arqueo_real' => $caja->monto_final,
            'diferencia' => $caja->monto_final - (($pagos_pdf['Efectivo'] ?? 0) - $gastos->sum('precio'))
        ];

        $pdf = Pdf::loadView('Ventas.pdf_caja', compact('caja', 'stats', 'gastos', 'ventas'));
        return $pdf->stream('Cierre_Caja_'.$id.'.pdf');
    }

    public function historial()
    {
        $id_sucursal = 1;
        $cajas = DB::table('Caja')
            ->leftJoin('Empleados', 'Caja.id_emp', '=', 'Empleados.id_emp')
            ->select('Caja.*', 'Empleados.nickName as cajero_nombre')
            ->where('Caja.id_suc', $id_sucursal)
            ->where('Caja.status', 0)
            ->orderBy('Caja.fecha_cierre', 'desc')
            ->paginate(15);

        return view('Ventas.historial_cajas', compact('cajas'));
    }

    public function abrirCaja(Request $request)
    {
        $request->validate(['monto_inicial' => 'required|numeric|min:0']);
        if(DB::table('Caja')->where('status', 1)->where('id_suc', 1)->exists()) return redirect()->back()->with('error', 'Caja abierta.');
        DB::table('Caja')->insert([
            'id_suc' => 1, 'id_emp' => Auth::user()->id_emp, 'fecha_apertura' => Carbon::now(),
            'monto_inicial' => $request->monto_inicial, 'status' => 1, 'observaciones_apertura' => $request->observaciones ?? 'Apertura'
        ]);
        return redirect()->route('flujo.caja.index')->with('success', 'Turno iniciado.');
    }

    public function cerrarCaja(Request $request, $id)
    {
        $request->validate(['monto_final' => 'required|numeric']);
        DB::table('Caja')->where('id_caja', $id)->update([
            'fecha_cierre' => Carbon::now(), 'monto_final' => $request->monto_final,
            'observaciones_cierre' => $request->observaciones_cierre, 'status' => 0 
        ]);
        return redirect()->route('flujo.caja.index')->with('success', 'Turno cerrado.')->with('download_pdf', $id);
    }
}