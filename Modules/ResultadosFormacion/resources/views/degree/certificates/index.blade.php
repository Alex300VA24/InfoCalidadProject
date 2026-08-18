<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-end gap-3">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Certificados</h2>
                <p class="text-slate-500">Emisión de certificados de estudios, prácticas y constancias.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('degree.certificates.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span class="material-symbols-outlined text-lg">workspace_premium</span>
                    Emitir Certificado
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
                            <select name="type" onchange="this.form.submit()" aria-label="Filtrar por tipo" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los tipos</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="student_id" onchange="this.form.submit()" aria-label="Filtrar por estudiante" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los estudiantes</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->codigo }} - {{ $student->fullName() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(count(request()->except(['page'])) > 0)
                                <a href="{{ route('degree.certificates.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors" title="Quitar filtros">
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
                                <th class="px-6 py-4">Código</th>
                                <th class="px-6 py-4">Estudiante</th>
                                <th class="px-6 py-4">Tipo</th>
                                <th class="px-6 py-4">Concepto</th>
                                <th class="px-6 py-4">Emisión</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($certificates as $certificate)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-navy">{{ $certificate->code }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold">{{ $certificate->student?->fullName() }}</span>
                                        <span class="block text-xs text-slate-400">{{ $certificate->student?->codigo }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $certificate->typeLabel() }}</td>
                                    <td class="px-6 py-4 max-w-xs truncate">{{ $certificate->concept }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $certificate->issued_at?->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border text-emerald-700 bg-emerald-100 border-emerald-200">
                                            {{ ucfirst($certificate->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a data-turbo="false" href="{{ route('degree.certificates.download', $certificate) }}" class="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy" title="Descargar">
                                            <span class="material-symbols-outlined text-lg">download</span>
                                        </a>
                                        <a href="{{ route('degree.certificates.show', $certificate) }}" title="Ver detalle" aria-label="Ver detalle del certificado" class="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay certificados emitidos</p>
                                        <p class="text-xs mt-1">Emite el primer certificado</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $certificates->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
