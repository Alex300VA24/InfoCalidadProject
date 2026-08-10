<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Detalle de Sesión</h2>
                <p class="text-slate-500">{{ $classSession->subject?->name }}</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-3xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-base font-bold text-navy">{{ $classSession->topic }}</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border
                        {{ $classSession->status === 'realizada' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ($classSession->status === 'planificada' ? 'text-blue-700 bg-blue-100 border-blue-200' : ($classSession->status === 'reprogramada' ? 'text-amber-700 bg-amber-100 border-amber-200' : 'text-red-700 bg-red-100 border-red-200')) }}">
                        {{ $classSession->statusLabel() }}
                    </span>
                </div>
                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Asignatura</dt>
                        <dd class="font-semibold">{{ $classSession->subject?->code }} - {{ $classSession->subject?->name }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Carrera</dt>
                        <dd class="font-semibold">{{ $classSession->subject?->career?->name }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Periodo</dt>
                        <dd class="font-semibold">{{ $classSession->academicPeriod?->name }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Docente</dt>
                        <dd class="font-semibold">{{ $classSession->teacher?->name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Fecha</dt>
                        <dd class="font-semibold">{{ $classSession->session_date?->format('d/m/Y') }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Horas</dt>
                        <dd class="font-semibold">{{ $classSession->hours }}</dd>
                    </div>
                    @if($classSession->notes)
                        <div class="flex flex-wrap gap-x-4 gap-y-1 px-6 py-3">
                            <dt class="text-slate-500">Observaciones</dt>
                            <dd class="font-semibold max-w-full sm:max-w-md text-left sm:text-right min-w-0">{{ $classSession->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('execution.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
            </div>
        </div>
    </div>
</x-app-layout>
