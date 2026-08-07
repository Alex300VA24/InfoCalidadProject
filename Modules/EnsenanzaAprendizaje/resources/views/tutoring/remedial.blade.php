<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Tutoría Académica</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Nivelación y Recuperación</h2>
                <p class="text-slate-500">Programas de nivelación y recuperación académica.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('tutoring.remedial.create') }}" class="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">+ Registrar Programa</a>
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
                                <option value="">Todos los periodos</option>
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}" {{ request('academic_period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
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
                                <th class="px-6 py-4">Estudiante</th>
                                <th class="px-6 py-4">Periodo</th>
                                <th class="px-6 py-4">Asignatura</th>
                                <th class="px-6 py-4">Descripción</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($programs as $program)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-navy">{{ $program->student?->user?->name }}</span>
                                        <span class="block text-xs text-slate-400">{{ $program->student?->codigo }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $program->academicPeriod?->name }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $program->subject?->code ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate">{{ $program->description ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border
                                            {{ $program->status === 'completado' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ($program->status === 'reprobado' ? 'text-red-700 bg-red-100 border-red-200' : ($program->status === 'en_curso' ? 'text-blue-700 bg-blue-100 border-blue-200' : 'text-amber-700 bg-amber-100 border-amber-200')) }}">
                                            {{ $program->statusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST" action="{{ route('tutoring.remedial.status', $program) }}" class="inline">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200">
                                                @foreach($statuses as $key => $label)
                                                    <option value="{{ $key }}" {{ $program->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay programas de nivelación</p>
                                        <p class="text-xs mt-1">Registra el primer programa</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $programs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
