<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Evaluación del Estudiante</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Detalle de Evaluación</h2>
                <p class="text-slate-500">{{ $evaluation->student?->fullName() }} — {{ $evaluation->subject?->name }}</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-3xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-base font-bold text-navy">{{ $evaluation->typeLabel() }}</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border
                        {{ (float) $evaluation->score >= 14 ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ((float) $evaluation->score >= 10 ? 'text-amber-700 bg-amber-100 border-amber-200' : 'text-red-700 bg-red-100 border-red-200') }}">
                        {{ $evaluation->score }}
                    </span>
                </div>
                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Estudiante</dt>
                        <dd class="font-semibold text-navy">{{ $evaluation->student?->fullName() }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Código</dt>
                        <dd class="font-semibold">{{ $evaluation->student?->codigo }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Asignatura</dt>
                        <dd class="font-semibold">{{ $evaluation->subject?->code }} - {{ $evaluation->subject?->name }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Periodo</dt>
                        <dd class="font-semibold">{{ $evaluation->academicPeriod?->name }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Fecha</dt>
                        <dd class="font-semibold">{{ $evaluation->evaluation_date?->format('d/m/Y') }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Registrado por</dt>
                        <dd class="font-semibold">{{ $evaluation->registrar?->name ?? '—' }}</dd>
                    </div>
                    @if($evaluation->observations)
                        <div class="flex justify-between px-6 py-3">
                            <dt class="text-slate-500">Observaciones</dt>
                            <dd class="font-semibold max-w-md text-right">{{ $evaluation->observations }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('evaluations.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
            </div>
        </div>
    </div>
</x-app-layout>
