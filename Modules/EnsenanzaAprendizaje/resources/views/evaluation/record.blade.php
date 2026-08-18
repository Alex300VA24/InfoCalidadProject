<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Evaluación del Estudiante</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Acta de Notas</h2>
                <p class="text-slate-500">Promedios ponderados por asignatura y periodo académico.</p>
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
                                @foreach($periods as $p)
                                    <option value="{{ $p->id }}" {{ ($period?->id ?? $p->id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="subject_id" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Seleccione asignatura</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}" {{ $subject?->id == $s->id ? 'selected' : '' }}>{{ $s->code }} - {{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Generar Acta</button>
                        </div>
                        @if($period && $subject)
                            <div class="flex justify-end">
                                <a data-turbo="false" href="{{ route('evaluations.acta-pdf', ['academic_period_id' => $period->id, 'subject_id' => $subject->id]) }}" target="_blank" class="w-full px-4 py-2 bg-accent text-ink font-black rounded-lg text-sm text-center hover:brightness-95 transition-all">Descargar PDF</a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            @if($period && $subject)
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-base font-bold text-navy">{{ $subject->code }} - {{ $subject->name }}</h3>
                        <span class="text-xs text-slate-400">Periodo {{ $period->name }}</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Código</th>
                                    <th class="px-6 py-4">Estudiante</th>
                                    <th class="px-6 py-4">P1</th>
                                    <th class="px-6 py-4">P2</th>
                                    <th class="px-6 py-4">P3</th>
                                    <th class="px-6 py-4">Parcial</th>
                                    <th class="px-6 py-4">Final</th>
                                    <th class="px-6 py-4">Promedio</th>
                                    <th class="px-6 py-4">Condición</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse($rows as $row)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 font-bold text-navy">{{ $row['student']->codigo }}</td>
                                        <td class="px-6 py-4 font-semibold">{{ $row['student']->fullName() }}</td>
                                        <td class="px-6 py-4">{{ $row['evaluations']->get('practica_1')?->score ?? '—' }}</td>
                                        <td class="px-6 py-4">{{ $row['evaluations']->get('practica_2')?->score ?? '—' }}</td>
                                        <td class="px-6 py-4">{{ $row['evaluations']->get('practica_3')?->score ?? '—' }}</td>
                                        <td class="px-6 py-4">{{ $row['evaluations']->get('examen_parcial')?->score ?? '—' }}</td>
                                        <td class="px-6 py-4">{{ $row['evaluations']->get('examen_final')?->score ?? '—' }}</td>
                                        <td class="px-6 py-4 font-bold text-navy">{{ $row['final'] ?? '—' }}</td>
                                        <td class="px-6 py-4">
                                            @if($row['final'] !== null)
                                                <span class="px-3 py-1 rounded-full text-xs font-bold border
                                                    {{ $row['final'] >= 14 ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ($row['final'] >= 10 ? 'text-amber-700 bg-amber-100 border-amber-200' : 'text-red-700 bg-red-100 border-red-200') }}">
                                                    {{ $row['final'] >= 14 ? 'Aprobado' : ($row['final'] >= 10 ? 'En Riesgo' : 'Desaprobado') }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 text-xs">Sin notas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-10 text-center text-slate-400">
                                            <p class="text-sm font-bold text-slate-600">No hay estudiantes matriculados en esta asignatura</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-10 text-center">
                    <p class="text-sm font-bold text-slate-600">Seleccione asignatura y periodo</p>
                    <p class="text-xs text-slate-400 mt-1">Para generar el acta de notas del periodo.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
