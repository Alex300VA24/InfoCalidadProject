<x-app-layout>
    <x-slot name="header">
        <div>
            <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Reportes · F5</span>
            <h2 class="text-3xl font-bold text-navy mt-2">Reporte de Egresados</h2>
            <p class="text-slate-500">Listado de estudiantes con historial académico para el reporte de egresados.</p>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Código</th>
                                <th class="px-6 py-4">Estudiante</th>
                                <th class="px-6 py-4">Ciclo</th>
                                <th class="px-6 py-4">Matrículas</th>
                                <th class="px-6 py-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($students as $student)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-navy">{{ $student->codigo }}</td>
                                    <td class="px-6 py-4 font-semibold">{{ $student->fullName() }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $student->ciclo }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $counts[$student->id] ?? 0 }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $student->estado === 'activo' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : 'text-slate-600 bg-slate-100 border-slate-200' }}">
                                            {{ ucfirst($student->estado) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay estudiantes registrados</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
