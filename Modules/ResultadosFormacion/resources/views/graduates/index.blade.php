<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-end gap-3">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Seguimiento de Egresados</h2>
                <p class="text-slate-500">Encuestas de inserción laboral y seguimiento de egresados.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('graduates.stats') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-lg">monitoring</span>
                    Estadísticas
                </a>
                <a href="{{ route('graduates.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span class="material-symbols-outlined text-lg">person_add</span>
                    Registrar Egresado
                </a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div class="p-4">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <select name="work_status" onchange="this.form.submit()" aria-label="Filtrar por situación laboral" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todas las situaciones</option>
                                @foreach($workStatuses as $key => $label)
                                    <option value="{{ $key }}" {{ request('work_status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(count(request()->except(['page'])) > 0)
                                <a href="{{ route('graduates.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors" title="Quitar filtros">
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
                                <th class="px-6 py-4">Egresado</th>
                                <th class="px-6 py-4">Situación Laboral</th>
                                <th class="px-6 py-4">Empleador</th>
                                <th class="px-6 py-4">Cargo</th>
                                <th class="px-6 py-4">Ingreso Mensual</th>
                                <th class="px-6 py-4">Encuesta</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($graduates as $graduate)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold">{{ $graduate->student?->fullName() }}</span>
                                        <span class="block text-xs text-slate-400">{{ $graduate->student?->codigo }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border
                                            {{ in_array($graduate->work_status, ['empleado', 'independiente']) ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ($graduate->work_status === 'desempleado' ? 'text-red-700 bg-red-100 border-red-200' : 'text-amber-700 bg-amber-100 border-amber-200') }}">
                                            {{ $graduate->workStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $graduate->employer ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $graduate->job_position ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $graduate->monthly_income ? 'S/ ' . number_format($graduate->monthly_income, 2) : '—' }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $graduate->survey_date?->format('d/m/Y') ?? 'Sin encuesta' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('graduates.show', $graduate) }}" title="Ver detalle" aria-label="Ver detalle del egresado" class="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay egresados registrados</p>
                                        <p class="text-xs mt-1">Registra el primer egresado</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $graduates->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
