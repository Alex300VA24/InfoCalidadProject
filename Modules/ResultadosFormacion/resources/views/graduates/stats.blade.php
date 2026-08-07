<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Estadísticas de Egresados</h2>
                <p class="text-slate-500">Indicadores de inserción laboral de los egresados.</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border border-slate-200 p-6 rounded-xl shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="material-symbols-outlined text-navy bg-navy/10 p-2 rounded-lg">badge</span>
                    </div>
                    <div class="text-3xl font-bold text-navy">{{ $total }}</div>
                    <div class="text-sm text-slate-500 font-medium mt-1">Egresados Registrados</div>
                </div>

                <div class="bg-white border border-slate-200 p-6 rounded-xl shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="material-symbols-outlined text-navy bg-navy/10 p-2 rounded-lg">payments</span>
                    </div>
                    <div class="text-3xl font-bold text-navy">S/ {{ number_format($averageIncome, 2) }}</div>
                    <div class="text-sm text-slate-500 font-medium mt-1">Ingreso Mensual Promedio</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h3 class="text-base font-bold text-navy">Situación Laboral</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @forelse($byStatus as $data)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-slate-700">{{ $data['status'] }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-navy/10 text-navy">
                                    {{ $data['total'] }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 text-center py-4">Sin datos</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('graduates.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
            </div>
        </div>
    </div>
</x-app-layout>
