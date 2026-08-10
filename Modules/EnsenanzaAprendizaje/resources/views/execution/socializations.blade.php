<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-end gap-3">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Socialización de Sílabos</h2>
                <p class="text-slate-500">Registro de la difusión de sílabos a los estudiantes.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('execution.socializations.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span class="material-symbols-outlined text-lg">campaign</span>
                    Registrar Socialización
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
                            <select name="subject_id" onchange="this.form.submit()" aria-label="Filtrar por asignatura" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todas las asignaturas</option>
                                @foreach($socializations->pluck('syllabus.subject')->filter()->unique('id') as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->code }} - {{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="registered_by" onchange="this.form.submit()" aria-label="Filtrar por docente" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los docentes</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('registered_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(count(request()->except(['page'])) > 0)
                                <a href="{{ route('execution.socializations.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors" title="Quitar filtros">
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
                                <th class="px-6 py-4">Sílabo</th>
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Registrado por</th>
                                <th class="px-6 py-4">Notas</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($socializations as $socialization)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-navy">{{ $socialization->syllabus?->subject?->code }}</span>
                                        <span class="block text-xs text-slate-400">{{ $socialization->syllabus?->subject?->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-slate-700">v{{ $socialization->syllabus?->version ?? '—' }}</span>
                                        <span class="block text-xs text-slate-400">{{ $socialization->syllabus?->career?->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $socialization->date?->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $socialization->registeredBy?->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate">{{ $socialization->notes ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay socializaciones registradas</p>
                                        <p class="text-xs mt-1">Registra la primera socialización de sílabo</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $socializations->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
