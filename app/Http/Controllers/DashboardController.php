<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $hoy = Carbon::today();

        // 1. Base de Ventas (Status 1 = Pagado)
        $queryVentas = DB::table('Venta')
            ->whereDate('fecha_hora', $hoy)
            ->where('status', 1);

        $ventasHoy = (float)($queryVentas->sum('total') ?? 0);
        $numVentas = (int)($queryVentas->count());
        
        // 2. Desglose por Métodos (Cards de colores)
        try {
            $col = DB::getSchemaBuilder()->hasColumn('Venta', 'metodo_pago') ? 'metodo_pago' : 'metodo';
            
            $efectivoVentas = (float)((clone $queryVentas)->where($col, 'Efectivo')->sum('total') ?? 0);
            $tarjetasHoy = (float)((clone $queryVentas)->where($col, 'Tarjeta')->sum('total') ?? 0);
            $transferenciasHoy = (float)((clone $queryVentas)->where($col, 'Transferencia')->sum('total') ?? 0);
        } catch (\Exception $e) {
            $efectivoVentas = 0; $tarjetasHoy = 0; $transferenciasHoy = 0;
        }

        // 3. Gastos
        try {
            $gastosHoy = (float)(DB::table('gastos')->whereDate('fecha_hora', $hoy)->sum('monto') ?? 0);
        } catch (\Exception $e) {
            $gastosHoy = 0;
        }

        // 4. Dinero Real en Caja
        $efectivoCaja = $efectivoVentas - $gastosHoy;

        // 5. Últimas ventas (Confirmado: id_venta)
        $ultimasVentas = DB::table('Venta')
            ->orderByDesc('id_venta')
            ->limit(8)
            ->get();

        $data = [
            'ventasHoy' => $ventasHoy,
            'numVentas' => $numVentas,
            'gastosHoy' => $gastosHoy,
            'efectivoCaja' => $efectivoCaja,
            'efectivoVentas' => $efectivoVentas,
            'tarjetasHoy' => $tarjetasHoy,
            'transferenciasHoy' => $transferenciasHoy,
        ];

        if ($request->ajax()) {
            return response()->json($data);
        }

        return view('dashboard', array_merge($data, ['ultimasVentas' => $ultimasVentas]));
    }
}