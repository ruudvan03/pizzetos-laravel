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
        $cajaAbierta = DB::table('Caja')->where('status', 1)->where('id_suc', $id_sucursal)->first();

        // 1. PIZZAS NORMALES (Cat 12)
        $pizzas_raw = DB::table('Pizzas')->join('Especialidades', 'Pizzas.id_esp', '=', 'Especialidades.id_esp')->join('TamanosPizza', 'Pizzas.id_tamano', '=', 'TamanosPizza.id_tamañop')->select('Especialidades.nombre', 'TamanosPizza.tamano', 'TamanosPizza.precio', 'Pizzas.id_pizza')->get();
        $pizzas = [];
        foreach($pizzas_raw as $p) {
            if(!isset($pizzas[$p->nombre])) $pizzas[$p->nombre] = ['nombre' => $p->nombre, 'tamanos' => []];
            $pizzas[$p->nombre]['tamanos'][] = ['id' => $p->id_pizza, 'tamano' => $p->tamano, 'precio' => $p->precio];
        }

        // 2. MARISCOS (Cat 2)
        $mariscos_raw = DB::table('PizzasMariscos')->join('TamanosPizza', 'PizzasMariscos.id_tamañop', '=', 'TamanosPizza.id_tamañop')->select('PizzasMariscos.nombre', 'TamanosPizza.tamano', 'TamanosPizza.precio', 'PizzasMariscos.id_maris')->get();
        $mariscos = [];
        foreach($mariscos_raw as $m) {
            $nom = str_replace('Pizza ', '', $m->nombre);
            if(!isset($mariscos[$nom])) $mariscos[$nom] = ['nombre' => $nom, 'tamanos' => []];
            $mariscos[$nom]['tamanos'][] = ['id' => $m->id_maris, 'tamano' => $m->tamano, 'precio' => $m->precio];
        }

        // 3. BEBIDAS AGRUPADAS (Cat 1) - Para crear sub-menús (Pepsi, Bebidas Calientes, etc.)
        $bebidas_raw = DB::table('Refrescos')->join('TamanosRefrescos', 'Refrescos.id_tamano', '=', 'TamanosRefrescos.id_tamano')->select('Refrescos.id_refresco as id', 'Refrescos.nombre', 'TamanosRefrescos.tamano', 'TamanosRefrescos.precio')->get();
        $bebidas = [];
        foreach($bebidas_raw as $b) {
            if(!isset($bebidas[$b->nombre])) $bebidas[$b->nombre] = ['nombre' => $b->nombre, 'cat' => 1, 'opciones' => []];
            $bebidas[$b->nombre]['opciones'][] = ['id' => $b->id, 'tamano' => $b->tamano, 'precio' => $b->precio];
        }

        // 4. PRODUCTOS DIRECTOS RESTANTES (Hamburguesas, Alitas, Costillas, Spaguetty, Papas, Magno...)
        $directos = [];
        $rectangular = DB::table('Rectangular')->join('Especialidades', 'Rectangular.id_esp', '=', 'Especialidades.id_esp')->select('Rectangular.id_rec as id', 'Especialidades.nombre', 'Rectangular.precio')->get();
        foreach($rectangular as $r) { $directos[] = ['id' => $r->id, 'col' => 'id_rec', 'nombre' => $r->nombre, 'precio' => $r->precio, 'cat' => 11]; }

        $barra = DB::table('Barra')->join('Especialidades', 'Barra.id_especialidad', '=', 'Especialidades.id_esp')->select('Barra.id_barr as id', 'Especialidades.nombre', 'Barra.precio')->get();
        foreach($barra as $b) { $directos[] = ['id' => $b->id, 'col' => 'id_barr', 'nombre' => $b->nombre, 'precio' => $b->precio, 'cat' => 10]; }

        foreach(DB::table('Hamburguesas')->get() as $h) { $directos[] = ['id' => $h->id_hamb, 'col' => 'id_hamb', 'nombre' => $h->paquete, 'precio' => $h->precio, 'cat' => 6]; }
        foreach(DB::table('Alitas')->get() as $a) { $directos[] = ['id' => $a->id_alis, 'col' => 'id_alis', 'nombre' => $a->orden, 'precio' => $a->precio, 'cat' => 5]; }
        foreach(DB::table('Costillas')->get() as $c) { $directos[] = ['id' => $c->id_cos, 'col' => 'id_cos', 'nombre' => $c->orden, 'precio' => $c->precio, 'cat' => 7]; }
        foreach(DB::table('Spaguetty')->get() as $s) { $directos[] = ['id' => $s->id_spag, 'col' => 'id_spag', 'nombre' => $s->orden, 'precio' => $s->precio, 'cat' => 9]; }
        foreach(DB::table('OrdenDePapas')->get() as $p) { $directos[] = ['id' => $p->id_papa, 'col' => 'id_papa', 'nombre' => $p->orden, 'precio' => $p->precio, 'cat' => 8]; }

        // 5. DATOS PARA MODALES Y GLOBALES
        $paquetes = DB::table('Paquetes')->get();
        $ingredientes = DB::table('Ingredientes')->get();
        $tamanos_base = DB::table('TamanosPizza')->where('tamano', 'like', '%Especial%')->get(); 
        $especialidades_lista = DB::table('Especialidades')->get();
        
        // Categorías Dinámicas: Toma todas las categorías excepto Pizzas, Mariscos, Rectangular y Barra
        $categorias_extras = DB::table('CategoriasProd')->whereNotIn('id_cat', [12, 2, 11, 10])->get();

        return view('Ventas.pos', [
            'cajaAbierta' => $cajaAbierta, 'pizzas' => array_values($pizzas), 'mariscos' => array_values($mariscos),
            'bebidas' => array_values($bebidas), 'directos' => $directos, 'paquetes' => $paquetes, 
            'ingredientes' => $ingredientes, 'tamanos_base' => $tamanos_base, 'especialidades_lista' => $especialidades_lista, 
            'categorias_extras' => $categorias_extras
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $id_sucursal = Auth::check() ? (Auth::user()->id_suc ?? 1) : 1;
            $cajaAbierta = DB::table('Caja')->where('status', 1)->where('id_suc', $id_sucursal)->first();
            
            if(!$cajaAbierta) throw new \Exception("No hay caja abierta.");

            $id_venta = DB::table('Venta')->insertGetId([
                'id_suc' => $id_sucursal, 'id_caja' => $cajaAbierta->id_caja,
                'total' => $request->total, 'tipo_servicio' => $request->tipo_servicio,
                'mesa' => $request->mesa, 'comentarios' => $request->comentarios,
                'status' => 0, 'fecha_hora' => Carbon::now()
            ]);

            foreach($request->carrito as $item) {
                $datosJson = [];
                if(isset($item['comentario']) && $item['comentario'] !== '') $datosJson['nota'] = $item['comentario'];
                if(isset($item['ingredientes_extra'])) $datosJson['ingredientes_extra'] = $item['ingredientes_extra'];
                if(isset($item['cuartos'])) $datosJson['cuartos'] = $item['cuartos'];
                if(isset($item['medios'])) $datosJson['medios'] = $item['medios'];

                $datosInsert = [
                    'id_venta' => $id_venta, 'cantidad' => $item['qty'], 'precio_unitario' => $item['precioFinal'],
                    'queso' => (isset($item['orilla_queso']) && $item['orilla_queso']) ? 1 : 0,
                    'ingredientes' => count($datosJson) > 0 ? json_encode($datosJson) : null
                ];

                if (isset($item['tipo']) && $item['tipo'] == 'paq') {
                    $datosInsert['id_paquete'] = (string) $item['db_id'];
                } elseif (isset($item['tipo']) && $item['tipo'] == 'piz_mitad') {
                    $datosInsert['id_pizza'] = null;
                    $datosInsert['pizza_mitad'] = json_encode(['mitad1' => $item['mitad1'], 'mitad2' => $item['mitad2'], 'tamano' => $item['tamano']]);
                } else {
                    $datosInsert[$item['col']] = $item['db_id'];
                }

                DB::table('DetalleVenta')->insert($datosInsert);
            }

            if ($request->has('pagos')) {
                foreach($request->pagos as $pago) {
                    DB::table('Pago')->insert(['id_venta' => $id_venta, 'id_metpago' => $pago['id_metpago'], 'monto' => $pago['monto']]);
                }
            }

            if ($request->tipo_servicio == 3 && $request->has('id_clie') && $request->id_clie && $request->has('id_dir') && $request->id_dir) {
                DB::table('PDomicilio')->insert(['id_venta' => $id_venta, 'id_clie' => $request->id_clie, 'id_dir' => $request->id_dir]);
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
        $venta = DB::table('Venta')->where('id_venta', $id)->first();
        if(!$venta) abort(404);
        $detalles = DB::table('DetalleVenta')->where('id_venta', $id)->get();
        $pagos = DB::table('Pago')->where('id_venta', $id)->get();
        return view('Ventas.ticket', compact('venta', 'detalles', 'pagos'));
    }
}