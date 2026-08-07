<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Gestión del Ingreso</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Convocatorias de Admisión</h2>
                <p class="text-slate-500">Administra las convocatorias, vacantes y resultados de postulantes.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admission.processes.create') }}" class="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">+ Nueva Convocatoria</a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Convocatoria</th>
                                <th class="px-6 py-4">Periodo</th>
                                <th class="px-6 py-4">Carrera</th>
                                <th class="px-6 py-4">Vacantes</th>
                                <th class="px-6 py-4">Ingresantes</th>
                                <th class="px-6 py-4">Cobertura</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($processes as $process)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admission.processes.show', $process) }}" class="font-bold text-navy hover:underline">{{ $process->name }}</a>
                                        <div class="text-xs text-slate-400">{{ $process->modality }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $process->academicPeriod?->name }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $process->career?->code }}</td>
                                    <td class="px-6 py-4">{{ $process->vacancies }}</td>
                                    <td class="px-6 py-4">{{ $process->ingresantesCount() }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                                <div class="h-full {{ $process->coveragePercentage() >= 100 ? 'bg-emerald-500' : 'bg-navy' }}" style="width: {{ min($process->coveragePercentage(), 100) }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold">{{ $process->coveragePercentage() }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border
                                            {{ $process->status === 'activo' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ($process->status === 'cerrado' ? 'text-slate-600 bg-slate-100 border-slate-200' : 'text-amber-700 bg-amber-100 border-amber-200') }}">
                                            {{ ucfirst($process->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admission.processes.show', $process) }}" class="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy" title="Ver">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                        <a href="{{ route('admission.processes.edit', $process) }}" class="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy" title="Editar">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay convocatorias</p>
                                        <p class="text-xs mt-1">Crea la primera convocatoria de admisión</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $processes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
