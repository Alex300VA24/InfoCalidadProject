<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Movilidad Académica y Becas</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Detalle de Solicitud</h2>
                <p class="text-slate-500">{{ $mobilityApplication->student?->fullName() }} — {{ $mobilityApplication->typeLabel() }}</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-3xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-base font-bold text-navy">{{ $mobilityApplication->typeLabel() }}</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border
                        {{ in_array($mobilityApplication->status, ['aprobada', 'finalizada']) ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : (in_array($mobilityApplication->status, ['en_curso']) ? 'text-blue-700 bg-blue-100 border-blue-200' : ($mobilityApplication->status === 'rechazada' ? 'text-red-700 bg-red-100 border-red-200' : 'text-amber-700 bg-amber-100 border-amber-200')) }}">
                        {{ $mobilityApplication->statusLabel() }}
                    </span>
                </div>
                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Estudiante</dt>
                        <dd class="font-semibold text-navy">{{ $mobilityApplication->student?->fullName() }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Código</dt>
                        <dd class="font-semibold">{{ $mobilityApplication->student?->codigo }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Periodo</dt>
                        <dd class="font-semibold">{{ $mobilityApplication->academicPeriod?->name }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Institución de Destino</dt>
                        <dd class="font-semibold">{{ $mobilityApplication->destination_institution ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Programa</dt>
                        <dd class="font-semibold">{{ $mobilityApplication->program_name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Beca</dt>
                        <dd class="font-semibold">{{ $mobilityApplication->scholarship_name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Fecha de Solicitud</dt>
                        <dd class="font-semibold">{{ $mobilityApplication->application_date?->format('d/m/Y') }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Duración</dt>
                        <dd class="font-semibold">
                            {{ $mobilityApplication->start_date?->format('d/m/Y') ?? '—' }} a {{ $mobilityApplication->end_date?->format('d/m/Y') ?? '—' }}
                        </dd>
                    </div>
                    @if($mobilityApplication->notes)
                        <div class="px-6 py-3">
                            <dt class="text-slate-500 mb-1">Observaciones</dt>
                            <dd class="font-semibold">{{ $mobilityApplication->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-navy">Actualizar Estado</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('mobility.status', $mobilityApplication) }}" class="flex gap-3">
                        @csrf
                        <select name="status" class="flex-1 rounded-lg border-slate-200 text-sm">
                            @foreach(\Modules\EnsenanzaAprendizaje\Models\MobilityApplication::STATUSES as $key => $label)
                                <option value="{{ $key }}" {{ $mobilityApplication->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Actualizar</button>
                    </form>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('mobility.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
            </div>
        </div>
    </div>
</x-app-layout>
