<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Investigación Formativa</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Detalle de Proyecto</h2>
                <p class="text-slate-500">{{ $researchProject->title }}</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-3xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-base font-bold text-navy">{{ $researchProject->title }}</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border
                        {{ in_array($researchProject->status, ['aprobado']) ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : (in_array($researchProject->status, ['finalizado']) ? 'text-blue-700 bg-blue-100 border-blue-200' : ($researchProject->status === 'rechazado' ? 'text-red-700 bg-red-100 border-red-200' : 'text-amber-700 bg-amber-100 border-amber-200')) }}">
                        {{ $researchProject->statusLabel() }}
                    </span>
                </div>
                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Estudiante</dt>
                        <dd class="font-semibold text-navy">{{ $researchProject->student?->fullName() }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Código</dt>
                        <dd class="font-semibold">{{ $researchProject->student?->codigo }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Periodo</dt>
                        <dd class="font-semibold">{{ $researchProject->academicPeriod?->name }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Área</dt>
                        <dd class="font-semibold">{{ $researchProject->area ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Asesor</dt>
                        <dd class="font-semibold">{{ $researchProject->advisor?->name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Duración</dt>
                        <dd class="font-semibold">
                            {{ $researchProject->start_date?->format('d/m/Y') ?? '—' }} a {{ $researchProject->end_date?->format('d/m/Y') ?? '—' }}
                        </dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Nota</dt>
                        <dd class="font-semibold text-navy">{{ $researchProject->score ?? '—' }}</dd>
                    </div>
                    @if($researchProject->description)
                        <div class="px-6 py-3">
                            <dt class="text-slate-500 mb-1">Descripción</dt>
                            <dd class="font-semibold">{{ $researchProject->description }}</dd>
                        </div>
                    @endif
                    @if($researchProject->document_path)
                        <div class="flex justify-between px-6 py-3">
                            <dt class="text-slate-500">Documento</dt>
                            <dd class="font-semibold">
                                <a data-turbo="false" href="{{ route('research.download', $researchProject) }}" class="inline-flex items-center gap-1 text-navy hover:underline">
                                    <span class="material-symbols-outlined text-lg">download</span> Descargar
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-navy">Actualizar Estado</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('research.status', $researchProject) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @csrf
                        <select name="status" class="rounded-lg border-slate-200 text-sm">
                            @foreach(\Modules\EnsenanzaAprendizaje\Models\ResearchProject::STATUSES as $key => $label)
                                <option value="{{ $key }}" {{ $researchProject->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="score" value="{{ $researchProject->score }}" min="0" max="20" step="0.01" placeholder="Nota (0-20)" class="rounded-lg border-slate-200 text-sm">
                        <div class="col-span-2">
                            <button type="submit" class="w-full px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('research.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
            </div>
        </div>
    </div>
</x-app-layout>
