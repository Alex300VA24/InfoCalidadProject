<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Registrar Evaluación de Desempeño</h2>
                <p class="text-slate-500">Registra la evaluación de desempeño de un docente.</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <form method="POST" action="{{ route('execution.performance.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Docente</label>
                                <select name="teacher_id" required class="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="">Seleccione docente</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Periodo Académico</label>
                                <select name="academic_period_id" required class="w-full rounded-lg border-slate-200 text-sm">
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ old('academic_period_id', $defaultPeriod?->id) == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Fuente</label>
                                <select name="source" class="w-full rounded-lg border-slate-200 text-sm">
                                    @foreach($sources as $key => $label)
                                        <option value="{{ $key }}" {{ old('source', 'encuesta_estudiante') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Evaluación</label>
                                <input type="date" name="evaluated_at" value="{{ old('evaluated_at', now()->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nota (0 - 20)</label>
                                <input type="number" name="score" value="{{ old('score') }}" min="0" max="20" step="0.01" required class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
                                <textarea name="observations" rows="3" class="w-full rounded-lg border-slate-200 text-sm">{{ old('observations') }}</textarea>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="flex justify-end">
                            <a href="{{ route('execution.performance.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Registrar Evaluación</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
