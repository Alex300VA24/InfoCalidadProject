<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-end gap-3">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Movilidad Académica y Becas</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Solicitudes de Movilidad</h2>
                <p class="text-slate-500">Gestión de movilidad nacional, internacional y becas.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('mobility.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span class="material-symbols-outlined text-lg">flight_takeoff</span>
                    Nueva Solicitud
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
                            <select name="type" onchange="this.form.submit()" aria-label="Filtrar por tipo" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los tipos</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
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
                                <a href="{{ route('mobility.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors" title="Quitar filtros">
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
                                <th class="px-6 py-4">Tipo</th>
                                <th class="px-6 py-4">Institución Destino</th>
                                <th class="px-6 py-4">Periodo</th>
                                <th class="px-6 py-4">Fecha Solicitud</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($applications as $application)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold">{{ $application->student?->fullName() }}</span>
                                        <span class="block text-xs text-slate-400">{{ $application->student?->codigo }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $application->typeLabel() }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $application->destination_institution ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $application->academicPeriod?->name }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $application->application_date?->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border
                                            {{ in_array($application->status, ['aprobada', 'finalizada']) ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : (in_array($application->status, ['en_curso']) ? 'text-blue-700 bg-blue-100 border-blue-200' : ($application->status === 'rechazada' ? 'text-red-700 bg-red-100 border-red-200' : 'text-amber-700 bg-amber-100 border-amber-200')) }}">
                                            {{ $application->statusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('mobility.show', $application) }}" title="Ver detalle" aria-label="Ver detalle de la solicitud" class="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay solicitudes registradas</p>
                                        <p class="text-xs mt-1">Registra la primera solicitud de movilidad</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $applications->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
