<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Desempeño Docente</h2>
                <p class="text-slate-500">Evaluaciones del desempeño docente por fuente y periodo.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('execution.performance.create') }}" class="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">+ Registrar Evaluación</a>
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
                            <select name="teacher_id" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los docentes</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="source" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todas las fuentes</option>
                                @foreach($sources as $key => $label)
                                    <option value="{{ $key }}" {{ request('source') === $key ? 'selected' : '' }}>{{ $label }}</option>
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
                                <th class="px-6 py-4">Docente</th>
                                <th class="px-6 py-4">Periodo</th>
                                <th class="px-6 py-4">Fuente</th>
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Nota</th>
                                <th class="px-6 py-4">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($evaluations as $evaluation)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-navy">{{ $evaluation->teacher?->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $evaluation->academicPeriod?->name }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $evaluation->sourceLabel() }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $evaluation->evaluated_at?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-navy">{{ number_format((float) $evaluation->score, 2) }}</span>
                                        <span class="text-xs text-slate-400">/ 20</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate">{{ $evaluation->observations ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay evaluaciones de desempeño</p>
                                        <p class="text-xs mt-1">Registra la primera evaluación</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $evaluations->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
