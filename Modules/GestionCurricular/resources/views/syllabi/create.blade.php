<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subir Sílabo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('syllabi.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Carrera</label>
                                <select name="career_id" id="career_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 text-sm">
                                    @foreach($careers as $career)
                                        <option value="{{ $career->id }}" {{ $defaultCareer?->id === $career->id ? 'selected' : '' }}>{{ $career->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Asignatura</label>
                                <select name="subject_id" id="subject_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 text-sm">
                                    <option value="">Seleccione asignatura</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Periodo Académico</label>
                                <select name="academic_period_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 text-sm">
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ $period->is_active ? 'selected' : '' }}>{{ $period->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Docente</label>
                                <select name="teacher_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 text-sm">
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Archivo PDF</label>
                            <input type="file" name="file" accept=".pdf" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">Máximo 20MB, formato PDF</p>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('syllabi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Subir Sílabo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('career_id')?.addEventListener('change', function () {
            const subjectSelect = document.getElementById('subject_id');
            subjectSelect.innerHTML = '<option value="">Cargando asignaturas...</option>';
            fetch("{{ route('syllabi.subjects') }}?career_id=" + this.value)
                .then(res => res.json())
                .then(subjects => {
                    subjectSelect.innerHTML = '<option value="">Seleccione asignatura</option>' +
                        subjects.map(s => `<option value="${s.id}">${s.code} - ${s.name}</option>`).join('');
                });
        });
    </script>
</x-app-layout>
