<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Movilidad y Becas</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Registrar Convenio</h2>
                <p class="text-slate-500">Registra un nuevo convenio interinstitucional.</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <form method="POST" action="{{ route('mobility.agreements.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nombre del Convenio</label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Institución</label>
                                <input type="text" name="institution" value="{{ old('institution') }}" required class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                                <select name="type" class="w-full rounded-lg border-slate-200 text-sm">
                                    @foreach($types as $key => $label)
                                        <option value="{{ $key }}" {{ old('type', 'nacional') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                                <select name="status" class="w-full rounded-lg border-slate-200 text-sm">
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ old('status', 'vigente') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Inicio</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Fin</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                                <textarea name="description" rows="3" class="w-full rounded-lg border-slate-200 text-sm">{{ old('description') }}</textarea>
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
                            <a href="{{ route('mobility.agreements.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Registrar Convenio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
