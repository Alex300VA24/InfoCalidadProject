<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Padrón Virtual</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Listado Oficial de Matriculados</h2>
                <p class="text-slate-500">Periodo {{ $period?->name ?? 'sin definir' }}</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div class="p-4 flex items-center justify-between gap-4">
                    <form method="GET" class="flex-1 max-w-sm">
                        <select name="academic_period_id" onchange="this.form.submit()" class="w-full rounded-lg border-slate-200 text-sm">
                            @foreach($periods as $p)
                                <option value="{{ $p->id }}" {{ $period?->id === $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </form>
                    <button onclick="window.print()" class="px-4 py-2 bg-navy text-white rounded-lg text-sm font-bold hover:bg-[#343d96] transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">print</span> Imprimir
                    </button>
                </div>
            </div>

            @forelse($rows as $subjectName => $items)
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                        <h3 class="text-lg font-bold text-navy">{{ $subjectName }}</h3>
                        <p class="text-xs text-slate-400">{{ $items->count() }} matriculados</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <tr>
                                    <th class="px-6 py-3">N°</th>
                                    <th class="px-6 py-3">Código</th>
                                    <th class="px-6 py-3">Estudiante</th>
                                    <th class="px-6 py-3">Carrera</th>
                                    <th class="px-6 py-3">Matrícula</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @foreach($items as $index => $row)
                                    <tr>
                                        <td class="px-6 py-3">{{ $index + 1 }}</td>
                                        <td class="px-6 py-3">{{ $row->enrollment?->student?->codigo }}</td>
                                        <td class="px-6 py-3 font-semibold text-navy">{{ $row->enrollment?->student?->fullName() }}</td>
                                        <td class="px-6 py-3 text-slate-500">{{ $row->enrollment?->career?->code }}</td>
                                        <td class="px-6 py-3 text-slate-500">{{ $row->enrollment?->code }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white border-2 border-dashed border-slate-200 rounded-xl p-10 text-center">
                    <p class="text-sm font-bold text-slate-600">No hay matriculados para este periodo</p>
                    <p class="text-xs text-slate-400 mt-1">Registra matrículas para generar el padrón virtual</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
