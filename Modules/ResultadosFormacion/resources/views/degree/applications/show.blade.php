<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Detalle de Expediente</h2>
                <p class="text-slate-500">{{ $degreeApplication->code }} — {{ $degreeApplication->typeLabel() }}</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-3xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-base font-bold text-navy">{{ $degreeApplication->typeLabel() }}</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border
                        {{ in_array($degreeApplication->status, ['aprobado', 'otorgado']) ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ($degreeApplication->status === 'observado' ? 'text-red-700 bg-red-100 border-red-200' : 'text-amber-700 bg-amber-100 border-amber-200') }}">
                        {{ $degreeApplication->statusLabel() }}
                    </span>
                </div>
                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Expediente</dt>
                        <dd class="font-semibold text-navy">{{ $degreeApplication->code }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Estudiante</dt>
                        <dd class="font-semibold">{{ $degreeApplication->student?->fullName() }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Código</dt>
                        <dd class="font-semibold">{{ $degreeApplication->student?->codigo }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Fecha de solicitud</dt>
                        <dd class="font-semibold">{{ $degreeApplication->application_date?->format('d/m/Y') }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Asesor</dt>
                        <dd class="font-semibold">{{ $degreeApplication->advisor?->name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Resolución</dt>
                        <dd class="font-semibold">{{ $degreeApplication->resolution_number ?? '—' }} {{ $degreeApplication->resolution_date ? '(' . $degreeApplication->resolution_date->format('d/m/Y') . ')' : '' }}</dd>
                    </div>
                    @if($degreeApplication->thesis_title)
                        <div class="px-6 py-3">
                            <dt class="text-slate-500 mb-1">Título de la tesis</dt>
                            <dd class="font-semibold">{{ $degreeApplication->thesis_title }}</dd>
                        </div>
                    @endif
                    @if($degreeApplication->notes)
                        <div class="px-6 py-3">
                            <dt class="text-slate-500 mb-1">Observaciones</dt>
                            <dd class="font-semibold">{{ $degreeApplication->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-navy">Actualizar Estado</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('degree.applications.status', $degreeApplication) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @csrf
                        <select name="status" class="rounded-lg border-slate-200 text-sm">
                            @foreach(\Modules\ResultadosFormacion\Models\DegreeApplication::STATUSES as $key => $label)
                                <option value="{{ $key }}" {{ $degreeApplication->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="resolution_number" value="{{ $degreeApplication->resolution_number }}" placeholder="N° de resolución" class="rounded-lg border-slate-200 text-sm">
                        <input type="date" name="resolution_date" value="{{ $degreeApplication->resolution_date?->format('Y-m-d') }}" class="rounded-lg border-slate-200 text-sm">
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('degree.applications.acts.index', $degreeApplication) }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-base align-text-bottom mr-1">description</span>
                    Actas de grado
                </a>
                <a href="{{ route('degree.applications.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
            </div>
        </div>
    </div>
</x-app-layout>
