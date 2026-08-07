<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Cargas Académicas</h2>
                <p class="text-slate-500">Distribución de asignaturas a docentes por periodo académico.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('execution.loads.create') }}" class="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">+ Registrar Carga</a>
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
                            <select name="teacher_id" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los docentes</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
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
                                <th class="px-6 py-4">Asignatura</th>
                                <th class="px-6 py-4">Periodo</th>
                                <th class="px-6 py-4">Sección</th>
                                <th class="px-6 py-4">Horas</th>
                                <th class="px-6 py-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($loads as $load)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-navy">{{ $load->teacher?->name ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-slate-700">{{ $load->subject?->code }}</span>
                                        <span class="block text-xs text-slate-400">{{ $load->subject?->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $load->academicPeriod?->name }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $load->section ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $load->hours }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border
                                            {{ $load->status === 'confirmado' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ($load->status === 'reemplazo' ? 'text-amber-700 bg-amber-100 border-amber-200' : 'text-blue-700 bg-blue-100 border-blue-200') }}">
                                            {{ $load->statusLabel() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay cargas académicas registradas</p>
                                        <p class="text-xs mt-1">Registra la primera carga del periodo</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $loads->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
