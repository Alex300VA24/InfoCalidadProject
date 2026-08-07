<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Registrar Socialización</h2>
                <p class="text-slate-500">Registra la socialización de un sílabo a los estudiantes.</p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <form method="POST" action="{{ route('execution.socializations.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Sílabo</label>
                                <select name="syllabus_id" required class="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="">Seleccione sílabo</option>
                                    @foreach($syllabi as $syllabus)
                                        <option value="{{ $syllabus->id }}" {{ old('syllabus_id') == $syllabus->id ? 'selected' : '' }}>
                                            {{ $syllabus->subject?->code }} - {{ $syllabus->subject?->name }} (v{{ $syllabus->version }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Socialización</label>
                                <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required class="w-full rounded-lg border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Registrado por</label>
                                <select name="registered_by" class="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="">Usuario actual</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('registered_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Notas</label>
                                <textarea name="notes" rows="3" class="w-full rounded-lg border-slate-200 text-sm">{{ old('notes') }}</textarea>
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
                            <a href="{{ route('execution.socializations.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Registrar Socialización</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
