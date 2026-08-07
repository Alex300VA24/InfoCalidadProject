<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Encuesta de Seguimiento</h2>
                <p class="text-slate-500">{{ $graduate->student?->fullName() }}</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <form method="POST" action="{{ route('graduates.surveys.store', $graduate) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Periodo</label>
                                <input type="text" name="period" value="{{ old('period') }}" placeholder="Ej. 2026-II" required class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de encuesta</label>
                                <input type="date" name="survey_date" value="{{ old('survey_date', now()->format('Y-m-d')) }}" required class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">¿Se encuentra empleado?</label>
                                <select name="employed" class="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="0" {{ old('employed') == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('employed') == 1 ? 'selected' : '' }}>Sí</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">¿El empleo se relaciona con la carrera?</label>
                                <select name="job_related_to_career" class="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="">Sin especificar</option>
                                    <option value="0" {{ old('job_related_to_career') == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('job_related_to_career') == 1 ? 'selected' : '' }}>Sí</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nivel de logro de competencias (0 - 20)</label>
                                <input type="number" name="competency_level_score" value="{{ old('competency_level_score') }}" min="0" max="20" step="0.01" class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Ingreso mensual (S/)</label>
                                <input type="number" name="income" value="{{ old('income') }}" min="0" step="0.01" class="w-full rounded-lg border-slate-200 text-sm">
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
                            <a href="{{ route('graduates.show', $graduate) }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Registrar Encuesta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
