@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto">
    <div class="mb-12">
        <h2 class="text-6xl font-black text-gray-900 italic tracking-tighter uppercase leading-[0.8]">Personal</h2>
        <div class="h-2 w-24 bg-[#eab308] mt-6 rounded-full"></div>
    </div>

    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em]">Listado oficial de colaboradores</p>
            <button class="bg-[#eab308] hover:bg-black hover:text-white text-black px-8 py-4 rounded-2xl font-black text-xs transition-all shadow-xl shadow-yellow-500/20 uppercase tracking-[0.2em]">
                + Registrar Empleado
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="text-gray-400 uppercase text-[9px] tracking-[0.3em] font-black bg-gray-50/50">
                        <th class="px-8 py-6 border-b border-gray-50">Nombre</th>
                        <th class="px-8 py-6 border-b border-gray-50">Dirección</th>
                        <th class="px-8 py-6 border-b border-gray-50">Teléfono</th>
                        <th class="px-8 py-6 border-b border-gray-50">Cargo</th>
                        <th class="px-8 py-6 border-b border-gray-50">Sucursal</th>
                        <th class="px-8 py-6 border-b border-gray-50">Usuario</th>
                        <th class="px-8 py-6 border-b border-gray-50 text-center">Estado</th>
                        <th class="px-8 py-6 border-b border-gray-50 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($empleados as $empleado)
                    <tr class="hover:bg-yellow-50/30 transition-all group">
                        <td class="px-8 py-8 font-black text-gray-900 italic uppercase tracking-tighter text-lg">
                            {{ $empleado->nombre }}
                        </td>

                        <td class="px-8 py-8 text-sm text-gray-500 font-medium">
                            {{ $empleado->direccion ?? 'Sin dirección' }}
                        </td>

                        <td class="px-8 py-8">
                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-[11px] font-black tracking-wider italic">
                                {{ $empleado->telefono }}
                            </span>
                        </td>

                        <td class="px-8 py-8">
                            <span class="text-gray-800 font-black text-sm uppercase italic">
                                {{ $empleado->cargo->nombre ?? 'Staff' }}
                            </span>
                        </td>

                        <td class="px-8 py-8">
                            <span class="text-[#eab308] font-black text-[10px] uppercase tracking-widest">
                                {{ $empleado->sucursal->nombre ?? 'Matriz' }}
                            </span>
                        </td>

                        <td class="px-8 py-8">
                            <span class="text-gray-400 font-bold text-xs">
                                @ {{ $empleado->nickName }}
                            </span>
                        </td>

                        <td class="px-8 py-8">
                            <div class="flex justify-center">
                                @if($empleado->status == 1)
                                    <span class="flex items-center gap-2 text-green-600 font-black text-[9px] uppercase tracking-widest bg-green-50 px-3 py-1.5 rounded-full border border-green-100">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Activo
                                    </span>
                                @else
                                    <span class="flex items-center gap-2 text-gray-300 font-black text-[9px] uppercase tracking-widest bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100">
                                        <span class="w-1.5 h-1.5 bg-gray-200 rounded-full"></span> Inactivo
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-8 py-8 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                <button class="p-3 bg-white text-gray-400 hover:text-black hover:bg-[#eab308] rounded-xl shadow-sm border border-gray-100 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button class="p-3 bg-white text-gray-400 hover:text-white hover:bg-black rounded-xl shadow-sm border border-gray-100 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection