<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Ejecución de Asignaturas</h2>
                <p class="text-slate-500">Avance porcentual de las asignaturas por periodo académico.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('execution.executions.create') }}" class="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">+ Registrar Ejecución</a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div class="p-4">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <select name="academic_period_id" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los periodos</option>
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}" {{ request('academic_period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="subject_id" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todas las asignaturas</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->code }} - {{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="status" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los estados</option>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Asignatura</th>
                                <th class="px-6 py-4">Periodo</th>
                                <th class="px-6 py-4">Docente</th>
                                <th class="px-6 py-4">Avance</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($executions as $execution)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-navy">{{ $execution->subject?->code }}</span>
                                        <span class="block text-xs text-slate-400">{{ $execution->subject?->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $execution->academicPeriod?->name }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $execution->teacher?->name ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-32 h-2 rounded-full bg-slate-200 overflow-hidden">
                                                <div class="h-full rounded-full {{ (float) $execution->progress_pct >= 100 ? 'bg-emerald-500' : 'bg-accent' }}"
                                                    style="width: {{ min(100, (float) $execution->progress_pct) }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-slate-600">{{ $execution->progress_pct }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border
                                            {{ $execution->status === 'cerrado' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : 'text-blue-700 bg-blue-100 border-blue-200' }}">
                                            {{ $execution->statusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if (!$execution->isClosed())
                                            <form method="POST" action="{{ route('execution.executions.close', $execution) }}" class="inline"
                                                onsubmit="return confirm('¿Cerrar esta ejecución de asignatura?');">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition-colors">
                                                    <span class="material-symbols-outlined text-base">lock</span>
                                                    Cerrar
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay ejecuciones registradas</p>
                                        <p class="text-xs mt-1">Registra la primera ejecución de asignatura</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $executions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
