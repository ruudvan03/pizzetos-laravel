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

        // 1. Base de Ventas del día (Pagadas)
        $queryVentas = DB::table('Venta')
            ->whereDate('fecha_hora', $hoy)
            ->where('status', 1);

        $ventasHoy = (float)($queryVentas->sum('total') ?? 0);
        $numVentas = (int)($queryVentas->count());
        
        // 2. Desglose por Métodos con detección de columna
        try {
            $col = DB::getSchemaBuilder()->hasColumn('Venta', 'metodo_pago') ? 'metodo_pago' : 'metodo';
            
            // Usamos LIKE para mayor compatibilidad con mayúsculas/minúsculas
            $efectivoVentas = (float)((clone $queryVentas)->where($col, 'LIKE', 'Efectivo%')->sum('total') ?? 0);
            $tarjetasHoy = (float)((clone $queryVentas)->where($col, 'LIKE', 'Tarjeta%')->sum('total') ?? 0);
            $transferenciasHoy = (float)((clone $queryVentas)->where($col, 'LIKE', 'Transferencia%')->sum('total') ?? 0);
        } catch (\Exception $e) {
            $efectivoVentas = 0; $tarjetasHoy = 0; $transferenciasHoy = 0;
        }

        // 3. Gastos del día
        try {
            $gastosHoy = (float)(DB::table('gastos')->whereDate('fecha_hora', $hoy)->sum('monto') ?? 0);
        } catch (\Exception $e) {
            $gastosHoy = 0;
        }

        // 4. Dinero Real en Caja (Lo que entró en efectivo menos lo que salió en gastos)
        $efectivoCaja = $efectivoVentas - $gastosHoy;

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

        return view('dashboard', $data);
    }
}