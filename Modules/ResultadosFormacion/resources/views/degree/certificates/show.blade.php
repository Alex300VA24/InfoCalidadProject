<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Detalle de Certificado</h2>
                <p class="text-slate-500">{{ $certificate->code }} — {{ $certificate->typeLabel() }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('degree.certificates.download', $certificate) }}" class="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">Descargar PDF</a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-3xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-base font-bold text-navy">{{ $certificate->typeLabel() }}</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border text-emerald-700 bg-emerald-100 border-emerald-200">
                        {{ ucfirst($certificate->status) }}
                    </span>
                </div>
                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Código</dt>
                        <dd class="font-semibold text-navy">{{ $certificate->code }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Estudiante</dt>
                        <dd class="font-semibold">{{ $certificate->student?->fullName() }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Código del estudiante</dt>
                        <dd class="font-semibold">{{ $certificate->student?->codigo }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Fecha de emisión</dt>
                        <dd class="font-semibold">{{ $certificate->issued_at?->format('d/m/Y') }}</dd>
                    </div>
                    <div class="flex justify-between px-6 py-3">
                        <dt class="text-slate-500">Emitido por</dt>
                        <dd class="font-semibold">{{ $certificate->issued_by }}</dd>
                    </div>
                    <div class="px-6 py-3">
                        <dt class="text-slate-500 mb-1">Concepto</dt>
                        <dd class="font-semibold">{{ $certificate->concept }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('degree.certificates.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
            </div>
        </div>
    </div>
</x-app-layout>
