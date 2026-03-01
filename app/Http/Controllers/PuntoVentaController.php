<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PuntoVentaController extends Controller
{
    public function index()
    {
        $id_sucursal = Auth::check() ? (Auth::user()->id_suc ?? 1) : 1;
        $cajaAbierta = DB::table('caja')->where('status', 1)->where('id_suc', $id_sucursal)->first();

        // Traemos información clave de tu BD
        $clientes = DB::table('clientes')->where('status', 1)->get();
        $direcciones = DB::table('direcciones')->where('status', 1)->get();
        $categorias = DB::table('categoriasprod')->get();
        $paquetes = DB::table('paquetes')->get();

        $catalogo = [];

        // 1. HAMBURGUESAS (id_cat = 6)
        foreach(DB::table('hamburguesas')->get() as $h) { $catalogo[] = ['id_unico' => 'hamb_'.$h->id_hamb, 'db_id' => $h->id_hamb, 'tipo_columna' => 'id_hamb', 'nombre' => $h->paquete, 'precio' => $h->precio, 'id_cat' => $h->id_cat, 'color' => 'text-orange-600 bg-orange-50']; }
        
        // 2. ALITAS (id_cat = 5)
        foreach(DB::table('alitas')->get() as $a) { $catalogo[] = ['id_unico' => 'ali_'.$a->id_alis, 'db_id' => $a->id_alis, 'tipo_columna' => 'id_alis', 'nombre' => $a->orden, 'precio' => $a->precio, 'id_cat' => $a->id_cat, 'color' => 'text-red-600 bg-red-50']; }
        
        // 3. COSTILLAS (id_cat = 7)
        foreach(DB::table('costillas')->get() as $c) { $catalogo[] = ['id_unico' => 'cos_'.$c->id_cos, 'db_id' => $c->id_cos, 'tipo_columna' => 'id_cos', 'nombre' => $c->orden, 'precio' => $c->precio, 'id_cat' => $c->id_cat, 'color' => 'text-rose-600 bg-rose-50']; }
        
        // 4. SPAGUETTY (id_cat = 9)
        foreach(DB::table('spaguetty')->get() as $s) { $catalogo[] = ['id_unico' => 'spa_'.$s->id_spag, 'db_id' => $s->id_spag, 'tipo_columna' => 'id_spag', 'nombre' => $s->orden, 'precio' => $s->precio, 'id_cat' => $s->id_cat, 'color' => 'text-yellow-600 bg-yellow-50']; }
        
        // 5. PAPAS (id_cat = 8)
        foreach(DB::table('ordendepapas')->get() as $p) { $catalogo[] = ['id_unico' => 'pap_'.$p->id_papa, 'db_id' => $p->id_papa, 'tipo_columna' => 'id_papa', 'nombre' => $p->orden, 'precio' => $p->precio, 'id_cat' => $p->id_cat, 'color' => 'text-amber-600 bg-amber-50']; }
        
        // 6. REFRESCOS (id_cat = 1)
        $refrescos = DB::table('refrescos')->join('tamanosrefrescos', 'refrescos.id_tamano', '=', 'tamanosrefrescos.id_tamano')->select('refrescos.*', 'tamanosrefrescos.tamano', 'tamanosrefrescos.precio')->get();
        foreach($refrescos as $r) { $catalogo[] = ['id_unico' => 'ref_'.$r->id_refresco, 'db_id' => $r->id_refresco, 'tipo_columna' => 'id_refresco', 'nombre' => $r->nombre . ' (' . $r->tamano . ')', 'precio' => $r->precio, 'id_cat' => $r->id_cat, 'color' => 'text-blue-600 bg-blue-50']; }
        
        // 7. PIZZAS NORMALES (id_cat = 12)
        $pizzas = DB::table('pizzas')->join('especialidades', 'pizzas.id_esp', '=', 'especialidades.id_esp')->join('tamanospizza', 'pizzas.id_tamano', '=', 'tamanospizza.id_tamañop')->select('pizzas.id_pizza', 'especialidades.nombre as esp_nombre', 'tamanospizza.tamano', 'tamanospizza.precio', 'pizzas.id_cat')->get();
        foreach($pizzas as $p) { $catalogo[] = ['id_unico' => 'piz_'.$p->id_pizza, 'db_id' => $p->id_pizza, 'tipo_columna' => 'id_pizza', 'nombre' => 'Pizza ' . $p->esp_nombre . ' (' . str_replace([' Especial', ' Camaron', ' Mar'], '', $p->tamano) . ')', 'precio' => $p->precio, 'id_cat' => $p->id_cat, 'color' => 'text-green-600 bg-green-50']; }

        // 8. PIZZAS MARISCOS (id_cat = 2)
        $mariscos = DB::table('pizzasmariscos')->join('tamanospizza', 'pizzasmariscos.id_tamañop', '=', 'tamanospizza.id_tamañop')->select('pizzasmariscos.id_maris', 'pizzasmariscos.nombre', 'tamanospizza.tamano', 'tamanospizza.precio', 'pizzasmariscos.id_cat')->get();
        foreach($mariscos as $m) { $catalogo[] = ['id_unico' => 'mar_'.$m->id_maris, 'db_id' => $m->id_maris, 'tipo_columna' => 'id_maris', 'nombre' => $m->nombre . ' (' . str_replace([' Especial', ' Camaron', ' Mar'], '', $m->tamano) . ')', 'precio' => $m->precio, 'id_cat' => $m->id_cat, 'color' => 'text-teal-600 bg-teal-50']; }

        // 9. RECTANGULAR (id_cat = 11)
        $rectangular = DB::table('rectangular')->join('especialidades', 'rectangular.id_esp', '=', 'especialidades.id_esp')->select('rectangular.*', 'especialidades.nombre')->get();
        foreach($rectangular as $rec) { $catalogo[] = ['id_unico' => 'rec_'.$rec->id_rec, 'db_id' => $rec->id_rec, 'tipo_columna' => 'id_rec', 'nombre' => 'Rectangular ' . $rec->nombre, 'precio' => $rec->precio, 'id_cat' => $rec->id_cat, 'color' => 'text-indigo-600 bg-indigo-50']; }

        // 10. BARRA (id_cat = 10)
        $barra = DB::table('barra')->join('especialidades', 'barra.id_especialidad', '=', 'especialidades.id_esp')->select('barra.*', 'especialidades.nombre')->get();
        foreach($barra as $b) { $catalogo[] = ['id_unico' => 'bar_'.$b->id_barr, 'db_id' => $b->id_barr, 'tipo_columna' => 'id_barr', 'nombre' => 'Barra ' . $b->nombre, 'precio' => $b->precio, 'id_cat' => $b->id_cat, 'color' => 'text-purple-600 bg-purple-50']; }

        return view('ventas.pos', compact('cajaAbierta', 'categorias', 'catalogo', 'clientes', 'direcciones', 'paquetes'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $id_sucursal = Auth::check() ? (Auth::user()->id_suc ?? 1) : 1;
            $cajaAbierta = DB::table('caja')->where('status', 1)->where('id_suc', $id_sucursal)->first();
            if(!$cajaAbierta) throw new \Exception("No hay caja abierta.");

            // 1. Guardar VENTA 
            $id_venta = DB::table('venta')->insertGetId([
                'id_suc' => $id_sucursal,
                'id_caja' => $cajaAbierta->id_caja,
                'total' => $request->total,
                'tipo_servicio' => $request->tipo_servicio,
                'mesa' => $request->mesa,
                'nombreClie' => $request->nombre_cliente,
                'comentarios' => $request->comentarios,
                'status' => 0, 
                'fecha_hora' => Carbon::now()
            ]);

            // 2. Guardar DETALLES (Carrito)
            foreach($request->carrito as $item) {
                DB::table('detalleventa')->insert([
                    'id_venta' => $id_venta,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    $item['tipo_columna'] => $item['db_id']
                ]);
            }

            // 3. Guardar PAGOS
            foreach($request->pagos as $pago) {
                if($pago['monto'] > 0) {
                    DB::table('pago')->insert([
                        'id_venta' => $id_venta,
                        'id_metpago' => $pago['id_metpago'], 
                        'monto' => $pago['monto'],
                        'referencia' => $pago['referencia'] ?? null
                    ]);
                }
            }

            // 4. Guardar DOMICILIO (Si aplica)
            if ($request->tipo_servicio == 3 && $request->id_clie && $request->id_dir) {
                DB::table('pdomicilio')->insert([
                    'id_venta' => $id_venta,
                    'id_clie' => $request->id_clie,
                    'id_dir' => $request->id_dir
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'id_venta' => $id_venta]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function ticket($id)
    {
        $venta = DB::table('venta')->where('id_venta', $id)->first();
        if(!$venta) abort(404);

        $detalles = DB::table('detalleventa')->where('id_venta', $id)->get();
        $pagos = DB::table('pago')->join('metodospago', 'pago.id_metpago', '=', 'metodospago.id_metpago')->where('id_venta', $id)->get();
        $domicilio = DB::table('pdomicilio')->join('clientes', 'pdomicilio.id_clie', '=', 'clientes.id_clie')->join('direcciones', 'pdomicilio.id_dir', '=', 'direcciones.id_dir')->where('id_venta', $id)->first();

        return view('ventas.ticket', compact('venta', 'detalles', 'pagos', 'domicilio'));
    }
}