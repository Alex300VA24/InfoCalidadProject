<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Nueva Matrícula') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('enrollment.store') }}">
                        @csrf
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estudiante</label>
                                <select name="student_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                    <option value="">Seleccione estudiante</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->codigo }} - {{ $student->fullName() }}</option>
                                    @endforeach
                                </select>
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
                                <select name="career_id" id="career_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                                    @foreach($careers as $career)
                                        <option value="{{ $career->id }}" {{ $defaultCareer?->id === $career->id ? 'selected' : '' }}>{{ $career->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Derecho de matrícula (S/)</label>
                                <input type="number" name="matricula_fee" value="{{ old('matricula_fee', 0) }}" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Asignaturas a matricular</label>
                            <div id="subjects-container" class="grid grid-cols-1 md:grid-cols-2 gap-2 border rounded-md border-gray-300 p-4 max-h-72 overflow-y-auto">
                                <p class="text-sm text-slate-400 col-span-full">Seleccione la carrera para cargar las asignaturas.</p>
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

                        @if (session('error'))
                            <div class="mb-4 p-3 rounded-md bg-red-50 text-red-700 text-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="flex justify-end">
                            <a href="{{ route('enrollment.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Registrar Matrícula</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('subjects-container');
        const loadSubjects = (careerId) => {
            container.innerHTML = '<p class="text-sm text-slate-400 col-span-full">Cargando asignaturas...</p>';
            fetch("{{ route('enrollment.subjects') }}?career_id=" + careerId)
                .then(res => res.json())
                .then(subjects => {
                    if (!subjects.length) {
                        container.innerHTML = '<p class="text-sm text-slate-400 col-span-full">No hay asignaturas para esta carrera.</p>';
                        return;
                    }
                    container.innerHTML = subjects.map(s =>
                        `<label class="flex items-center gap-2 p-2 rounded hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="subjects[]" value="${s.id}" class="rounded border-slate-300 text-navy focus:ring-navy">
                            <span class="text-sm">${s.code} - ${s.name}</span>
                        </label>`
                    ).join('');
                });
        };
        document.getElementById('career_id')?.addEventListener('change', (e) => loadSubjects(e.target.value));
        loadSubjects(document.getElementById('career_id')?.value);
    </script>
</x-app-layout>
