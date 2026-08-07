<x-app-layout>
    <x-slot name="header">
        <div>
            <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Reportes · F1</span>
            <h2 class="text-3xl font-bold text-navy mt-2">Cronograma Académico</h2>
            <p class="text-slate-500">Periodos académicos y su actividad de matrícula.</p>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($periods as $period)
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="p-4 {{ $period->is_active ? 'bg-navy text-white' : 'bg-slate-100 text-slate-600' }}">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-bold">{{ $period->name }}</h3>
                                @if($period->is_active)
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded border bg-emerald-500/20 border-emerald-500/30 text-emerald-300">ACTIVO</span>
                                @endif
                            </div>
                            <p class="text-xs mt-1 opacity-70">{{ $period->start_date?->format('d/m/Y') }} — {{ $period->end_date?->format('d/m/Y') }}</p>
                        </div>
                        <div class="p-4 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Matrículas registradas</span>
                                <span class="font-bold text-navy">{{ $stats->get($period->id)?->total ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Carreras activas</span>
                                <span class="font-bold text-navy">{{ $stats->get($period->id)?->careers ?? 0 }}</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-navy" style="width: {{ min(($stats->get($period->id)?->total ?? 0) * 8, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-slate-400 py-10">
                        <p class="text-sm font-bold text-slate-600">No hay periodos académicos</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
