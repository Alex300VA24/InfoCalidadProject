<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Cuadro de Vacantes · F1</span>
                <h2 class="text-3xl font-bold text-navy mt-2">{{ $process->name }}</h2>
                <p class="text-slate-500">{{ $process->career?->name }} · {{ $process->academicPeriod?->name }}</p>
            </div>
            <div class="flex gap-3">
                @if($process->status !== 'cerrado')
                    <form method="POST" action="{{ route('admission.processes.finalize', $process) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                            {{ $process->status === 'borrador' ? 'Abrir Convocatoria' : 'Cerrar Convocatoria' }}
                        </button>
                    </form>
                @endif
                <a href="{{ route('admission.applicants.create') }}" class="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">+ Registrar Postulante</a>
                <a href="{{ route('admission.processes.edit', $process) }}" class="px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Editar</a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                    <div class="text-3xl font-bold text-navy">{{ $process->vacancies }}</div>
                    <div class="text-sm text-slate-500 font-medium mt-1">Vacantes</div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                    <div class="text-3xl font-bold text-navy">{{ $process->total_applicants }}</div>
                    <div class="text-sm text-slate-500 font-medium mt-1">Postulantes</div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                    <div class="text-3xl font-bold text-emerald-600">{{ $process->ingresantes }}</div>
                    <div class="text-sm text-slate-500 font-medium mt-1">Ingresantes</div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                    <div class="text-3xl font-bold text-navy">{{ $process->coveragePercentage() }}%</div>
                    <div class="text-sm text-slate-500 font-medium mt-1">Cobertura de vacantes</div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-navy">Postulantes</h3>
                    <span class="text-xs bg-navy/10 text-navy px-3 py-1 rounded-full">{{ $process->modality }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">DNI</th>
                                <th class="px-6 py-4">Apellidos y Nombres</th>
                                <th class="px-6 py-4">Puntaje</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($process->applicants as $applicant)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">{{ $applicant->dni }}</td>
                                    <td class="px-6 py-4 font-semibold text-navy">{{ $applicant->fullName() }}</td>
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
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                        <p class="text-sm font-bold text-slate-600">Aún no hay postulantes</p>
                                        <p class="text-xs mt-1">Registra el primer postulante de esta convocatoria</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
