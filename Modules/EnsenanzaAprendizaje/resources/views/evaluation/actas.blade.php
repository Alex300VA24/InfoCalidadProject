<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Enseñanza-aprendizaje</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Actas Oficiales</h2>
                <p class="text-slate-500">Generación, descarga y cierre de actas oficiales por asignatura y periodo.</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div class="p-4">
                    <form method="POST" action="{{ route('evaluations.actas.generar') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        @csrf
                        <div class="md:col-span-5">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Asignatura</label>
                            <select name="subject_id" required class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Seleccione asignatura</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Periodo Académico</label>
                            <select name="academic_period_id" required class="w-full rounded-lg border-slate-200 text-sm">
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}">{{ $period->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                <span class="material-symbols-outlined text-lg">description</span>
                                Generar Acta
                            </button>
                        </div>
                    </form>
                </div>
            </div>

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
                            <select name="status" onchange="this.form.submit()" aria-label="Filtrar por estado" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los estados</option>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(count(request()->except(['page'])) > 0)
                                <a href="{{ route('evaluations.actas') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors" title="Quitar filtros">
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
                                <th class="px-6 py-4">Asignatura</th>
                                <th class="px-6 py-4">Periodo</th>
                                <th class="px-6 py-4">Docente</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4">Cerrada el</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($acts as $act)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-navy">{{ $act->subject?->code }}</span>
                                        <span class="block text-xs text-slate-400">{{ $act->subject?->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $act->academicPeriod?->name }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $act->teacher?->name ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border
                                            {{ $act->status === 'cerrado' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : 'text-amber-700 bg-amber-100 border-amber-200' }}">
                                            {{ $act->statusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $act->closed_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a data-turbo="false" href="{{ route('evaluations.actas.download', $act) }}" class="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy" title="Descargar acta">
                                            <span class="material-symbols-outlined text-lg">download</span>
                                        </a>
                                        @if (!$act->isClosed())
                                            <form method="POST" action="{{ route('evaluations.actas.cerrar', $act) }}" class="inline"
                                                onsubmit="return confirm('¿Cerrar el acta definitivamente? No podrá modificarse.');">
                                                @csrf
                                                <button type="submit" class="inline-flex p-1.5 hover:bg-emerald-50 rounded text-emerald-700" title="Cerrar acta">
                                                    <span class="material-symbols-outlined text-lg">lock</span>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay actas generadas</p>
                                        <p class="text-xs mt-1">Genera la primera acta oficial desde el formulario superior</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $acts->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
