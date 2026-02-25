@extends('layouts.app')

@section('content')
<div class="w-full bg-white rounded-xl shadow-sm border border-gray-100 p-8 min-h-[400px]">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h2 class="text-2xl font-black text-[#0f172a] tracking-tight">Todos los Pedidos</h2>
            <p class="text-sm text-gray-500 mt-1">Historial completo de pedidos</p>
        </div>
        
        <form action="{{ route('ventas.resume') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <select name="fecha" class="border border-gray-300 text-gray-700 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white min-w-[160px]">
                <option value="hoy" {{ $filtroFecha == 'hoy' ? 'selected' : '' }}>Hoy</option>
                <option value="semana" {{ $filtroFecha == 'semana' ? 'selected' : '' }}>Esta semana</option>
                <option value="mes" {{ $filtroFecha == 'mes' ? 'selected' : '' }}>Este mes</option>
                <option value="todos" {{ $filtroFecha == 'todos' ? 'selected' : '' }}>Todos los registros</option>
            </select>

            <select name="estado" class="border border-gray-300 text-gray-700 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white min-w-[160px]">
                <option value="todos" {{ $filtroEstado == 'todos' ? 'selected' : '' }}>Todos los estados</option>
                <option value="0" {{ $filtroEstado == '0' ? 'selected' : '' }}>Esperando</option>
                <option value="1" {{ $filtroEstado == '1' ? 'selected' : '' }}>Preparando</option>
                <option value="2" {{ $filtroEstado == '2' ? 'selected' : '' }}>Completado</option>
                <option value="3" {{ $filtroEstado == '3' ? 'selected' : '' }}>Cancelado</option>
            </select>

            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2 rounded-md text-sm font-bold flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Actualizar
            </button>
        </form>
    </div>

    @if($ventas->isEmpty())
        <div class="flex items-center justify-center py-20">
            <p class="text-gray-500 text-[15px]">No hay pedidos para mostrar</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 uppercase text-[10px] tracking-widest font-bold border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">ID VENTA</th>
                        <th class="px-6 py-4 font-semibold">FECHA / HORA</th>
                        <th class="px-6 py-4 font-semibold">CLIENTE / MESA</th>
                        <th class="px-6 py-4 font-semibold">TOTAL</th>
                        <th class="px-6 py-4 font-semibold text-center">ESTADO</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @foreach($ventas as $venta)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900">#{{ $venta->id_venta }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y h:i A') }}</td>
                            <td class="px-6 py-4 text-gray-700 font-medium">
                                {{ $venta->nombreClie ?? 'Mesa ' . $venta->mesa }}
                            </td>
                            <td class="px-6 py-4 font-black text-green-600">${{ number_format($venta->total, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($venta->status == 0)
                                    <span class="bg-gray-100 text-gray-600 font-bold px-3 py-1 rounded-full text-xs">Esperando</span>
                                @elseif($venta->status == 1)
                                    <span class="bg-blue-100 text-blue-600 font-bold px-3 py-1 rounded-full text-xs">Preparando</span>
                                @elseif($venta->status == 2)
                                    <span class="bg-green-100 text-green-700 font-bold px-3 py-1 rounded-full text-xs">Completado</span>
                                @elseif($venta->status == 3)
                                    <span class="bg-red-100 text-red-600 font-bold px-3 py-1 rounded-full text-xs">Cancelado</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 font-bold px-3 py-1 rounded-full text-xs">Desconocido</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection