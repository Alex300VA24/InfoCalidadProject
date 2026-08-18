<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Nueva Convocatoria de Admisión') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admission.processes.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nombre de la convocatoria</label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200" placeholder="Ej. Admisión Ordinaria 2026-II">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Periodo Académico</label>
                                <select name="academic_period_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ $period->is_active ? 'selected' : '' }}>{{ $period->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Carrera</label>
                                <select name="career_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                                    @foreach($careers as $career)
                                        <option value="{{ $career->id }}" {{ $defaultCareer?->id === $career->id ? 'selected' : '' }}>{{ $career->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Vacantes</label>
                                <input type="number" name="vacancies" value="{{ old('vacancies', 0) }}" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Modalidad</label>
                                <select name="modality" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                                    @foreach(['Ordinario', 'Extraordinario', 'CEPUNT', 'Titulados', 'Primeros Puestos'] as $modalidad)
                                        <option value="{{ $modalidad }}" {{ old('modality') === $modalidad ? 'selected' : '' }}>{{ $modalidad }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Inicio</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fin</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Estado</label>
                                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                                    @foreach(['borrador', 'activo', 'cerrado'] as $estado)
                                        <option value="{{ $estado }}" {{ old('status') === $estado ? 'selected' : '' }}>{{ ucfirst($estado) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="mb-4 p-3 rounded-md bg-red-50 text-red-700 text-sm">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="flex justify-end">
                            <a href="{{ route('admission.processes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Crear Convocatoria</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
