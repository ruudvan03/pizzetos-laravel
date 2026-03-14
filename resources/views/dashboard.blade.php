@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-10" x-data="dashboardApp()" x-init="initRealtime()">
     
    {{-- ENCABEZADO --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-4xl font-black text-slate-900 italic tracking-tighter uppercase leading-none">
                Panel de Control
            </h2>
            <p class="text-slate-400 font-bold uppercase tracking-[0.3em] text-[10px] italic mt-2">
                Sincronizado: <span class="text-amber-500" x-text="currentTime"></span>
            </p>
        </div>
        
        <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-2xl border border-slate-100 shadow-sm">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            <span class="text-[9px] font-black text-slate-500 uppercase italic tracking-widest">En Vivo</span>
        </div>
    </div>

    {{-- GRID PRINCIPAL --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- CARD: EFECTIVO REAL (Sustituye al fondo negro) --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-24 h-24 text-slate-900" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/></svg>
            </div>
            <span class="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] italic">Dinero Real en Caja</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-6xl font-black text-slate-900 italic tracking-tighter" x-text="'$' + formatMoney(stats.efectivoCaja)"></span>
            </div>
            <div class="mt-6 flex items-center gap-4">
                <div class="flex-1 h-2 bg-slate-50 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-400 rounded-full" style="width: 70%"></div>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase italic">Balance de Turno</span>
            </div>
        </div>

        {{-- CARD: VENTAS TOTALES --}}
        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm">
            <span class="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] italic">Venta Bruta</span>
            <h3 class="text-4xl font-black text-slate-900 mt-2 tracking-tighter italic" x-text="'$' + formatMoney(stats.ventasHoy)"></h3>
            <p class="text-[9px] font-bold text-green-500 mt-2 uppercase italic">+ Ingresos totales</p>
        </div>

        {{-- CARD: GASTOS --}}
        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm">
            <span class="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] italic">Gastos</span>
            <h3 class="text-4xl font-black text-red-500 mt-2 tracking-tighter italic" x-text="'-$' + formatMoney(stats.gastosHoy)"></h3>
            <p class="text-[9px] font-bold text-slate-300 mt-2 uppercase italic text-right">Salidas registradas</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- GRÁFICA DE MÉTODOS DE PAGO --}}
        <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm flex flex-col items-center">
            <h4 class="text-sm font-black text-slate-800 italic uppercase tracking-widest mb-8 text-center w-full">Distribución de Pagos</h4>
            <div class="relative w-48 h-48">
                <canvas id="chartPagos"></canvas>
            </div>
            <div class="mt-8 space-y-2 w-full">
                <div class="flex justify-between text-[10px] font-black uppercase italic">
                    <span class="text-green-500">Efectivo</span>
                    <span x-text="'$' + formatMoney(stats.efectivoVentas)"></span>
                </div>
                <div class="flex justify-between text-[10px] font-black uppercase italic">
                    <span class="text-blue-500">Tarjeta</span>
                    <span x-text="'$' + formatMoney(stats.tarjetasHoy)"></span>
                </div>
                <div class="flex justify-between text-[10px] font-black uppercase italic">
                    <span class="text-purple-500">Transf.</span>
                    <span x-text="'$' + formatMoney(stats.transferenciasHoy)"></span>
                </div>
            </div>
        </div>

        {{-- ACTIVIDAD RECIENTE --}}
        <div class="lg:col-span-2 bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-2xl font-black text-slate-800 italic uppercase tracking-tighter">Últimos Movimientos</h4>
                <div class="h-1 w-16 bg-amber-400 rounded-full"></div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody class="divide-y divide-slate-50">
                        <template x-for="venta in stats.ultimasVentas" :key="venta.id_venta">
                            <tr class="group">
                                <td class="py-4 font-black text-xs text-slate-900 italic" x-text="'#' + (venta.folio_virtual || venta.id_venta)"></td>
                                <td class="py-4 text-xs text-slate-500 font-bold uppercase" x-text="venta.nombreClie || 'Mostrador'"></td>
                                <td class="py-4 font-black text-sm text-slate-900 text-right" x-text="'$' + formatMoney(venta.total)"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Cargamos Chart.js desde CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function dashboardApp() {
    return {
        stats: {
            ventasHoy: {{ $ventasHoy }},
            gastosHoy: {{ $gastosHoy ?? 0 }},
            efectivoCaja: {{ $efectivoCaja ?? 0 }},
            efectivoVentas: {{ $efectivoVentas ?? 0 }}, // Asegúrate de enviar esta
            tarjetasHoy: {{ $tarjetasHoy ?? 0 }},
            transferenciasHoy: {{ $transferenciasHoy ?? 0 }},
            ultimasVentas: @json($ultimasVentas)
        },
        currentTime: '',
        chart: null,

        initRealtime() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
            this.initChart();
            
            setInterval(() => this.fetchStats(), 30000);
        },

        initChart() {
            const ctx = document.getElementById('chartPagos').getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [this.stats.efectivoVentas, this.stats.tarjetasHoy, this.stats.transferenciasHoy],
                        backgroundColor: ['#10b981', '#3b82f6', '#a855f7'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    cutout: '80%',
                    plugins: { legend: { display: false } }
                }
            });
        },

        async fetchStats() {
            try {
                let response = await fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                this.stats = await response.json();
                
                // Actualizar gráfica
                this.chart.data.datasets[0].data = [this.stats.efectivoVentas, this.stats.tarjetasHoy, this.stats.transferenciasHoy];
                this.chart.update();
            } catch (e) { console.warn('Error sync'); }
        },

        updateClock() {
            this.currentTime = new Date().toLocaleTimeString('es-MX', { hour12: true });
        },

        formatMoney(amount) {
            return parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}
</script>
@endsection