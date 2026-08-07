<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Seguimiento al Desempeño</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Registrar Tutoría</h2>
                <p class="text-slate-500">Registra una sesión de tutoría académica para un estudiante.</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <form method="POST" action="{{ route('tutoring.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Estudiante</label>
                                <select name="student_id" required class="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="">Seleccione estudiante</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->codigo }} - {{ $student->fullName() }}</option>
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
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tutor</label>
                                <select name="tutor_id" class="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="">Tutor actual</option>
                                    @foreach($tutors as $tutor)
                                        <option value="{{ $tutor->id }}" {{ old('tutor_id') == $tutor->id ? 'selected' : '' }}>{{ $tutor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de la Tutoría</label>
                                <input type="date" name="tutoring_date" value="{{ old('tutoring_date', now()->format('Y-m-d')) }}" required class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                                <select name="type" class="w-full rounded-lg border-slate-200 text-sm">
                                    @foreach($types as $key => $label)
                                        <option value="{{ $key }}" {{ old('type', 'acompanamiento') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                                <select name="status" class="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="pendiente" {{ old('status') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="atendida" {{ old('status') === 'atendida' ? 'selected' : '' }}>Atendida</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Motivo</label>
                                <textarea name="reason" rows="3" class="w-full rounded-lg border-slate-200 text-sm">{{ old('reason') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Resultado / Acuerdos</label>
                                <textarea name="outcome" rows="3" class="w-full rounded-lg border-slate-200 text-sm">{{ old('outcome') }}</textarea>
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
                            <a href="{{ route('tutoring.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Registrar Tutoría</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
