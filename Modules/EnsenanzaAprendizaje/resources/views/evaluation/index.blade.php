<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-end gap-3">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Evaluación del Estudiante</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Evaluaciones</h2>
                <p class="text-slate-500">Registro de notas de prácticas, parciales y finales por asignatura.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('evaluations.record') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-lg">edit_note</span>
                    Acta de Notas
                </a>
                <a href="{{ route('evaluations.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span class="material-symbols-outlined text-lg">fact_check</span>
                    Registrar Evaluación
                </a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div class="p-4">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <select name="academic_period_id" onchange="this.form.submit()" aria-label="Filtrar por periodo" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los periodos</option>
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}" {{ request('academic_period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="subject_id" onchange="this.form.submit()" aria-label="Filtrar por asignatura" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todas las asignaturas</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->code }} - {{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="student_id" onchange="this.form.submit()" aria-label="Filtrar por estudiante" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los estudiantes</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->codigo }} - {{ $student->fullName() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(count(request()->except(['page'])) > 0)
                                <a href="{{ route('evaluations.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors" title="Quitar filtros">
                                    <span class="material-symbols-outlined text-lg">filter_alt_off</span>
                                    Limpiar
                                </a>
                            @endif
                            <p class="text-xs text-slate-400">Los filtros se aplican automáticamente</p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Estudiante</th>
                                <th class="px-6 py-4">Asignatura</th>
                                <th class="px-6 py-4">Periodo</th>
                                <th class="px-6 py-4">Tipo</th>
                                <th class="px-6 py-4">Nota</th>
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($evaluations as $evaluation)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold">{{ $evaluation->student?->fullName() }}</span>
                                        <span class="block text-xs text-slate-400">{{ $evaluation->student?->codigo }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-navy">{{ $evaluation->subject?->code }}</span>
                                        <span class="block text-xs text-slate-400">{{ $evaluation->subject?->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $evaluation->academicPeriod?->name }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $evaluation->typeLabel() }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border
                                            {{ (float) $evaluation->score >= 14 ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ((float) $evaluation->score >= 10 ? 'text-amber-700 bg-amber-100 border-amber-200' : 'text-red-700 bg-red-100 border-red-200') }}">
                                            {{ $evaluation->score }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $evaluation->evaluation_date?->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('evaluations.show', $evaluation) }}" title="Ver detalle" aria-label="Ver detalle de la evaluación" class="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay evaluaciones registradas</p>
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
