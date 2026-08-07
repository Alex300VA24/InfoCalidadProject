<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Detalle de Egresado</h2>
                <p class="text-slate-500">{{ $graduate->student?->fullName() }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('graduates.surveys.create', $graduate) }}" class="px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">+ Encuesta</a>
                <a href="{{ route('graduates.edit', $graduate) }}" class="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">Editar</a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-3xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-base font-bold text-navy">Inserción Laboral</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border
                        {{ in_array($graduate->work_status, ['empleado', 'independiente']) ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ($graduate->work_status === 'desempleado' ? 'text-red-700 bg-red-100 border-red-200' : 'text-amber-700 bg-amber-100 border-amber-200') }}">
                        {{ $graduate->workStatusLabel() }}
                    </span>
                </div>
                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Egresado</dt>
                        <dd class="font-semibold text-navy">{{ $graduate->student?->fullName() }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Código</dt>
                        <dd class="font-semibold">{{ $graduate->student?->codigo }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Fecha de egreso</dt>
                        <dd class="font-semibold">{{ $graduate->graduation_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Empleador</dt>
                        <dd class="font-semibold">{{ $graduate->employer ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Cargo</dt>
                        <dd class="font-semibold">{{ $graduate->job_position ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Vínculo laboral</dt>
                        <dd class="font-semibold">{{ $graduate->employment_relationship ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Ingreso mensual</dt>
                        <dd class="font-semibold">{{ $graduate->monthly_income ? 'S/ ' . number_format($graduate->monthly_income, 2) : '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Fecha de encuesta</dt>
                        <dd class="font-semibold">{{ $graduate->survey_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
            @if($graduate->observations)
                        <div class="px-6 py-3">
                            <dt class="text-slate-500 mb-1">Observaciones</dt>
                            <dd class="font-semibold">{{ $graduate->observations }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            @if($graduate->surveys->isNotEmpty())
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h3 class="text-base font-bold text-navy">Encuestas de Seguimiento</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <tr>
                                    <th class="px-6 py-3">Periodo</th>
                                    <th class="px-6 py-3">Fecha</th>
                                    <th class="px-6 py-3">Empleado</th>
                                    <th class="px-6 py-3">Relación con la carrera</th>
                                    <th class="px-6 py-3">Nivel de competencias</th>
                                    <th class="px-6 py-3">Ingreso</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($graduate->surveys as $survey)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-3 text-slate-500">{{ $survey->period }}</td>
                                        <td class="px-6 py-3 text-slate-500">{{ $survey->survey_date?->format('d/m/Y') }}</td>
                                        <td class="px-6 py-3 text-slate-500">{{ $survey->employed ? 'Sí' : 'No' }}</td>
                                        <td class="px-6 py-3 text-slate-500">{{ $survey->job_related_to_career === null ? '—' : ($survey->job_related_to_career ? 'Sí' : 'No') }}</td>
                                        <td class="px-6 py-3 font-semibold text-navy">{{ $survey->competency_level_score !== null ? number_format((float) $survey->competency_level_score, 2) . ' / 20' : '—' }}</td>
                                        <td class="px-6 py-3 text-slate-500">{{ $survey->income ? 'S/ ' . number_format($survey->income, 2) : '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('graduates.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
            </div>
        </div>
    </div>
</x-app-layout>
