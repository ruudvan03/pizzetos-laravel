<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    /**
     * Lista todos los clientes activos.
     */
    public function index()
    {
        $clientes = DB::table('Clientes')->where('status', 1)->get();
        return view('Clientes.index', compact('clientes'));
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit($id)
    {
        // Buscamos por id_clie que es tu llave primaria
        $cliente = DB::table('Clientes')->where('id_clie', $id)->first();
        
        if (!$cliente) {
            return redirect()->route('clientes.index')->with('error', 'Cliente no encontrado');
        }

        // Traemos sus direcciones relacionadas
        $direcciones = DB::table('Direcciones')
            ->where('id_clie', $id)
            ->where('status', 1)
            ->get();
        
        return view('Clientes.edit', compact('cliente', 'direcciones'));
    }

    /**
     * Procesa la actualización del cliente y sus múltiples direcciones.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20'
        ]);

        try {
            DB::beginTransaction();

            // 1. Actualizar datos básicos del cliente
            // NOTA: No incluimos updated_at porque la tabla no tiene esa columna
            DB::table('Clientes')->where('id_clie', $id)->update([
                'nombre'   => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono
            ]);

            // 2. Actualizar cada dirección que se editó en el formulario
            if ($request->has('direcciones')) {
                foreach ($request->direcciones as $id_dir => $dirData) {
                    DB::table('Direcciones')->where('id_dir', $id_dir)->update([
                        'calle'      => $dirData['calle'],
                        'manzana'    => $dirData['manzana'] ?? '',
                        'lote'       => $dirData['lote'] ?? '',
                        'colonia'    => $dirData['colonia'] ?? '',
                        'referencia' => $dirData['referencia'] ?? ''
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Cliente y direcciones actualizados correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            // Si hay un error, regresamos con el mensaje exacto para saber qué pasó
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Realiza un borrado lógico del cliente.
     */
    public function destroy($id)
    {
        try {
            // Borrado lógico cambiando status a 0
            DB::table('Clientes')->where('id_clie', $id)->update(['status' => 0]);
            
            return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar el cliente.');
        }
    }
}