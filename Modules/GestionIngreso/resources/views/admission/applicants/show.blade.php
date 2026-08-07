<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Postulante</span>
                <h2 class="text-3xl font-bold text-navy mt-2">{{ $applicant->fullName() }}</h2>
                <p class="text-slate-500">DNI {{ $applicant->dni }} · {{ $applicant->admissionProcess?->name }}</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-7">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100">
                            <h3 class="text-xl font-semibold text-navy">Datos del Postulante</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">Apellidos</span><span class="font-semibold text-navy">{{ $applicant->paterno }} {{ $applicant->materno }}</span></div>
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">Nombres</span><span class="font-semibold text-navy">{{ $applicant->nombres }}</span></div>
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">DNI</span><span class="font-semibold text-navy">{{ $applicant->dni }}</span></div>
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">Carrera</span><span class="font-semibold text-navy">{{ $applicant->career?->name }}</span></div>
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">Correo</span><span class="font-semibold text-navy">{{ $applicant->email ?? '—' }}</span></div>
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">Teléfono</span><span class="font-semibold text-navy">{{ $applicant->telefono ?? '—' }}</span></div>
                            <div><span class="text-slate-400 block text-xs uppercase font-bold">Puntaje</span><span class="font-semibold text-navy">{{ $applicant->score ?? 'Sin registrar' }}</span></div>
                            <div>
                                <span class="text-slate-400 block text-xs uppercase font-bold">Estado</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold border inline-block mt-1
                                    {{ $applicant->status === 'ingresante' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : ($applicant->status === 'no_ingresante' ? 'text-red-700 bg-red-100 border-red-200' : 'text-amber-700 bg-amber-100 border-amber-200') }}">
                                    {{ ucfirst($applicant->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($applicant->status === 'ingresante' && $applicant->constancia_path)
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mt-6 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-600">verified</span>
                                <div>
                                    <p class="text-sm font-bold text-emerald-800">Constancia de ingreso (F-DAD-PG-017) disponible</p>
                                    <p class="text-xs text-emerald-600">El estudiante ya fue habilitado con acceso al sistema.</p>
                                </div>
                            </div>
                            <a href="{{ route('admission.applicants.constancia', $applicant) }}" class="px-4 py-2 bg-emerald-700 text-white rounded-lg text-sm font-bold hover:bg-emerald-600 transition-colors">Descargar PDF</a>
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100">
                            <h3 class="text-xl font-semibold text-navy">Registrar Resultado</h3>
                        </div>
                        <div class="p-6">
                            <form method="POST" action="{{ route('admission.applicants.result', $applicant) }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Puntaje (0 - 100)</label>
                                    <input type="number" name="score" value="{{ old('score', $applicant->score) }}" min="0" max="100" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Decisión</label>
                                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                                        <option value="ingresante" {{ old('status', $applicant->status) === 'ingresante' ? 'selected' : '' }}>Ingresante</option>
                                        <option value="no_ingresante" {{ old('status', $applicant->status) === 'no_ingresante' ? 'selected' : '' }}>No ingresante</option>
                                        <option value="postulante" {{ old('status', $applicant->status) === 'postulante' ? 'selected' : '' }}>Pendiente</option>
                                    </select>
                                </div>
                                <p class="text-xs text-slate-400 mb-4">Al marcar "Ingresante" se genera automáticamente la constancia de ingreso y se habilita la cuenta del estudiante.</p>
                                <button type="submit" class="w-full px-4 py-2 bg-navy text-white rounded-lg text-sm font-bold hover:bg-[#343d96] transition-colors">Guardar Resultado</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
