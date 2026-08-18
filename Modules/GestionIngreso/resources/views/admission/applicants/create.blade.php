<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Registrar Postulante') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admission.applicants.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Convocatoria</label>
                                <select name="admission_process_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                    @foreach($processes as $process)
                                        <option value="{{ $process->id }}" {{ request('process_id') == $process->id ? 'selected' : '' }}>{{ $process->name }} ({{ $process->academicPeriod?->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">DNI</label>
                                <input type="text" name="dni" value="{{ old('dni') }}" required maxlength="15" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Carrera</label>
                                <select name="career_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                                    @foreach($careers as $career)
                                        <option value="{{ $career->id }}">{{ $career->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido Paterno</label>
                                <input type="text" name="paterno" value="{{ old('paterno') }}" required maxlength="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido Materno</label>
                                <input type="text" name="materno" value="{{ old('materno') }}" maxlength="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nombres</label>
                                <input type="text" name="nombres" value="{{ old('nombres') }}" required maxlength="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono') }}" maxlength="20" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
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
                            <a href="{{ route('admission.applicants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
