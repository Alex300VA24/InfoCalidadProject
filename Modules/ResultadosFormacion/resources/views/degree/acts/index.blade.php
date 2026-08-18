<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-end gap-3">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Actas de Grado</h2>
                <p class="text-slate-500">{{ $degreeApplication->code }} — {{ $degreeApplication->student?->fullName() }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('degree.applications.acts.create', $degreeApplication) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span class="material-symbols-outlined text-lg">edit_note</span>
                    Registrar Acta
                </a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-5xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Tipo de acta</th>
                                <th class="px-6 py-4">Fecha de sesión</th>
                                <th class="px-6 py-4">Resultado</th>
                                <th class="px-6 py-4">Nota</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($acts as $act)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-navy">{{ $act->actTypeLabel() }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $act->session_date?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($act->result)
                                            <span class="px-3 py-1 rounded-full text-xs font-bold border
                                                {{ $act->result === 'aprobado' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : 'text-red-700 bg-red-100 border-red-200' }}">
                                                {{ $act->resultLabel() }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $act->score !== null ? number_format((float) $act->score, 2) . ' / 20' : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay actas registradas</p>
                                        <p class="text-xs mt-1">Registra la primera acta del expediente</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $acts->links() }}
            </div>

            <div class="flex flex-wrap justify-end mt-6">
                <a href="{{ route('degree.applications.show', $degreeApplication) }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Volver al expediente
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
