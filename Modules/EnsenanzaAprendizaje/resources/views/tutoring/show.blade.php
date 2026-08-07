<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Seguimiento al Desempeño</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Detalle de Tutoría</h2>
                <p class="text-slate-500">{{ $academicTutoring->student?->fullName() }} — {{ $academicTutoring->typeLabel() }}</p>
            </div>
            @if($academicTutoring->status === 'pendiente')
                <form method="POST" action="{{ route('tutoring.complete', $academicTutoring) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition-colors">Marcar Atendida</button>
                </form>
            @endif
        </div>
    </x-slot>

    <div>
        <div class="max-w-3xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-base font-bold text-navy">{{ $academicTutoring->typeLabel() }}</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border
                        {{ $academicTutoring->status === 'atendida' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ($academicTutoring->status === 'pendiente' ? 'text-amber-700 bg-amber-100 border-amber-200' : 'text-red-700 bg-red-100 border-red-200') }}">
                        {{ ucfirst($academicTutoring->status) }}
                    </span>
                </div>
                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Estudiante</dt>
                        <dd class="font-semibold text-navy">{{ $academicTutoring->student?->fullName() }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Código</dt>
                        <dd class="font-semibold">{{ $academicTutoring->student?->codigo }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Periodo</dt>
                        <dd class="font-semibold">{{ $academicTutoring->academicPeriod?->name }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Tutor</dt>
                        <dd class="font-semibold">{{ $academicTutoring->tutor?->name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Fecha</dt>
                        <dd class="font-semibold">{{ $academicTutoring->tutoring_date?->format('d/m/Y') }}</dd>
                    </div>
                    @if($academicTutoring->reason)
                        <div class="px-6 py-3">
                            <dt class="text-slate-500 mb-1">Motivo</dt>
                            <dd class="font-semibold">{{ $academicTutoring->reason }}</dd>
                        </div>
                    @endif
                    @if($academicTutoring->outcome)
                        <div class="px-6 py-3">
                            <dt class="text-slate-500 mb-1">Resultado / Acuerdos</dt>
                            <dd class="font-semibold">{{ $academicTutoring->outcome }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('tutoring.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
            </div>
        </div>
    </div>
</x-app-layout>
