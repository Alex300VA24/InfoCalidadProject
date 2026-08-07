<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Admisión</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Postulantes</h2>
                <p class="text-slate-500">Registro y resultados de postulantes por convocatoria.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admission.applicants.create') }}" class="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">+ Registrar Postulante</a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div class="p-4">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <select name="admission_process_id" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todas las convocatorias</option>
                                @foreach($processes as $process)
                                    <option value="{{ $process->id }}" {{ request('admission_process_id') == $process->id ? 'selected' : '' }}>{{ $process->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="career_id" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todas las carreras</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}" {{ request('career_id') == $career->id ? 'selected' : '' }}>{{ $career->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="status" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los estados</option>
                                <option value="postulante" {{ request('status') === 'postulante' ? 'selected' : '' }}>Postulante</option>
                                <option value="ingresante" {{ request('status') === 'ingresante' ? 'selected' : '' }}>Ingresante</option>
                                <option value="no_ingresante" {{ request('status') === 'no_ingresante' ? 'selected' : '' }}>No ingresante</option>
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
                                <th class="px-6 py-4">DNI</th>
                                <th class="px-6 py-4">Apellidos y Nombres</th>
                                <th class="px-6 py-4">Convocatoria</th>
                                <th class="px-6 py-4">Carrera</th>
                                <th class="px-6 py-4">Puntaje</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($applicants as $applicant)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">{{ $applicant->dni }}</td>
                                    <td class="px-6 py-4 font-semibold text-navy">{{ $applicant->fullName() }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $applicant->admissionProcess?->name }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $applicant->career?->code }}</td>
                                    <td class="px-6 py-4">{{ $applicant->score ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border
                                            {{ $applicant->status === 'ingresante' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ($applicant->status === 'no_ingresante' ? 'text-red-700 bg-red-100 border-red-200' : 'text-amber-700 bg-amber-100 border-amber-200') }}">
                                            {{ ucfirst($applicant->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admission.applicants.show', $applicant) }}" class="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">No hay postulantes</p>
                                        <p class="text-xs mt-1">Registra el primer postulante</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $applicants->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
