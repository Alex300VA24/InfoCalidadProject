<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Registrar Acta de Grado</h2>
                <p class="text-slate-500">{{ $degreeApplication->code }} — {{ $degreeApplication->student?->fullName() }}</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <form method="POST" action="{{ route('degree.applications.acts.store', $degreeApplication) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de acta</label>
                                <select name="act_type" class="w-full rounded-lg border-slate-200 text-sm">
                                    @foreach($actTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('act_type', 'sustentacion') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de sesión</label>
                                <input type="date" name="session_date" value="{{ old('session_date', now()->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Resultado</label>
                                <select name="result" class="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="">Pendiente</option>
                                    @foreach($results as $key => $label)
                                        <option value="{{ $key }}" {{ old('result') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nota (0 - 20)</label>
                                <input type="number" name="score" value="{{ old('score') }}" min="0" max="20" step="0.01" class="w-full rounded-lg border-slate-200 text-sm">
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
                            <a href="{{ route('degree.applications.acts.index', $degreeApplication) }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Registrar Acta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
