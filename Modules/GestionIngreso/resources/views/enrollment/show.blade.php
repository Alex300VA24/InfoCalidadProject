<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Matrícula · Ficha F3</span>
                <h2 class="text-3xl font-bold text-navy mt-2">{{ $enrollment->code }}</h2>
                <p class="text-slate-500">{{ $enrollment->student?->fullName() }} · {{ $enrollment->academicPeriod?->name }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('enrollment.ficha', $enrollment) }}" class="px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">download</span> Ficha de Matrícula
                </a>
                <a href="{{ route('enrollment.orden-pago', $enrollment) }}" class="px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">receipt_long</span> Orden de Pago
                </a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-7">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100">
                            <h3 class="text-xl font-semibold text-navy">Datos de la Matrícula</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">Estudiante</span><span class="font-semibold text-navy">{{ $enrollment->student?->fullName() }}</span></div>
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">Código</span><span class="font-semibold text-navy">{{ $enrollment->student?->codigo }}</span></div>
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">Periodo</span><span class="font-semibold text-navy">{{ $enrollment->academicPeriod?->name }}</span></div>
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">Carrera</span><span class="font-semibold text-navy">{{ $enrollment->career?->name }}</span></div>
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">Fecha de matrícula</span><span class="font-semibold text-navy">{{ $enrollment->enrolled_at?->format('d/m/Y') }}</span></div>
                            <div>
                                <span class="text-slate-400 block text-xs uppercase font-bold">Estado</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold border inline-block mt-1 text-emerald-700 bg-emerald-100 border-emerald-200">{{ ucfirst($enrollment->status) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mt-6">
                        <div class="px-6 py-4 border-b border-slate-100">
                            <h3 class="text-xl font-semibold text-navy">Asignaturas Matriculadas</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                    <tr>
                                        <th class="px-6 py-4">Código</th>
                                        <th class="px-6 py-4">Asignatura</th>
                                        <th class="px-6 py-4">Créditos</th>
                                        <th class="px-6 py-4">Condición</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-slate-100">
                                    @forelse($enrollment->subjects as $es)
                                        <tr>
                                            <td class="px-6 py-4">{{ $es->subject?->code }}</td>
                                            <td class="px-6 py-4 font-semibold text-navy">{{ $es->subject?->name }}</td>
                                            <td class="px-6 py-4">{{ $es->subject?->credits }}</td>
                                            <td class="px-6 py-4 text-slate-500">{{ ucfirst($es->status) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-6 text-center text-slate-400">Sin asignaturas</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100">
                            <h3 class="text-xl font-semibold text-navy">Órdenes de Pago</h3>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($enrollment->paymentOrders as $payment)
                                <div class="p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-bold text-navy">{{ $payment->concept }}</p>
                                            <p class="text-xs text-slate-400">S/ {{ number_format($payment->amount, 2) }}</p>
                                            @if($payment->receipt_number)
                                                <p class="text-xs text-slate-400">Recibo N° {{ $payment->receipt_number }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold border
                                                {{ $payment->status === 'pagado' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : 'text-amber-700 bg-amber-100 border-amber-200' }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                            @if($payment->pdf_path)
                                                <a href="{{ route('enrollment.orden-pago', $enrollment) }}" class="p-1.5 hover:bg-slate-100 rounded text-navy">
                                                    <span class="material-symbols-outlined text-lg">download</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    @if($payment->status !== 'pagado')
                                        <form method="POST" action="{{ route('enrollment.payments.register', $payment) }}" class="mt-3 flex gap-2">
                                            @csrf
                                            <input type="text" name="receipt_number" required placeholder="N° de recibo" class="flex-1 rounded-lg border-slate-200 text-sm">
                                            <button type="submit" class="px-3 py-2 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition-colors">
                                                Registrar Pago
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <p class="p-6 text-sm text-slate-400 text-center">Sin órdenes de pago</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
