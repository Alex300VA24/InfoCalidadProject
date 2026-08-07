<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Editar Egresado</h2>
                <p class="text-slate-500">{{ $graduate->student?->fullName() }}</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <form method="POST" action="{{ route('graduates.update', $graduate) }}">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Estudiante / Egresado</label>
                                <select name="student_id" required class="w-full rounded-lg border-slate-200 text-sm">
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ $graduate->student_id == $student->id ? 'selected' : '' }}>{{ $student->codigo }} - {{ $student->fullName() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Situación Laboral</label>
                                <select name="work_status" required class="w-full rounded-lg border-slate-200 text-sm">
                                    @foreach($workStatuses as $key => $label)
                                        <option value="{{ $key }}" {{ $graduate->work_status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Egreso</label>
                                <input type="date" name="graduation_date" value="{{ $graduate->graduation_date?->format('Y-m-d') }}" class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Empleador</label>
                                <input type="text" name="employer" value="{{ old('employer', $graduate->employer) }}" class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Cargo</label>
                                <input type="text" name="job_position" value="{{ old('job_position', $graduate->job_position) }}" class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Ingreso Mensual (S/)</label>
                                <input type="number" name="monthly_income" value="{{ old('monthly_income', $graduate->monthly_income) }}" min="0" step="0.01" class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Vínculo Laboral</label>
                                <input type="text" name="employment_relationship" value="{{ old('employment_relationship', $graduate->employment_relationship) }}" class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Encuesta</label>
                                <input type="date" name="survey_date" value="{{ $graduate->survey_date?->format('Y-m-d') }}" class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
                                <textarea name="observations" rows="3" class="w-full rounded-lg border-slate-200 text-sm">{{ old('observations', $graduate->observations) }}</textarea>
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
                            <button type="submit" class="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
