<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Avance de Ejecución</h2>
                <p class="text-slate-500">Horas ejecutadas vs. planificadas por asignatura en el periodo.</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div class="p-4">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <select name="academic_period_id" class="w-full rounded-lg border-slate-200 text-sm">
                                @foreach($periods as $p)
                                    <option value="{{ $p->id }}" {{ ($period?->id ?? $p->id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Ver Avance</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-navy">Cobertura por Asignatura — Periodo {{ $period?->name }}</h3>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($rows as $row)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-slate-700">
                                    {{ $row['subject']->code }} - {{ $row['subject']->name }}
                                    <span class="text-xs text-slate-400">({{ $row['subject']->career?->code }})</span>
                                </span>
                                <span class="text-slate-500">
                                    {{ $row['executed_hours'] }}/{{ $row['planned_hours'] }} h · {{ $row['sessions_count'] }} sesiones · {{ $row['percentage'] }}%
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $row['percentage'] >= 100 ? 'bg-emerald-500' : ($row['percentage'] >= 60 ? 'bg-navy' : 'bg-amber-500') }}" style="width: {{ min($row['percentage'], 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">Sin asignaturas registradas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
