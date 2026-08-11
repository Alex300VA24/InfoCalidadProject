<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $syllabus->subject->code }} - {{ $syllabus->subject->name }}
            </h2>
            <div class="flex space-x-2">
                <a data-turbo="false" href="{{ route('syllabi.download', $syllabus) }}" class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-md text-sm hover:bg-green-500">Descargar PDF</a>
                @if(!$syllabus->is_visado)
                    <form method="POST" action="{{ route('syllabi.visa', $syllabus) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-500">Visar Sílabo</button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-6">
                        <div><span class="text-gray-500">Asignatura:</span> <strong>{{ $syllabus->subject?->name }}</strong></div>
                        <div><span class="text-gray-500">Código:</span> <strong>{{ $syllabus->subject?->code }}</strong></div>
                        <div><span class="text-gray-500">Carrera:</span> <strong>{{ $syllabus->career?->name }}</strong></div>
                        <div><span class="text-gray-500">Periodo:</span> <strong>{{ $syllabus->academicPeriod?->name }}</strong></div>
                        <div><span class="text-gray-500">Docente:</span> <strong>{{ $syllabus->teacher?->name }}</strong></div>
                        <div><span class="text-gray-500">Versión:</span> <strong>{{ $syllabus->version }}</strong></div>
                        <div>
                            <span class="text-gray-500">Visado:</span>
                            @if($syllabus->is_visado)
                                <span class="text-green-600 font-bold">✓ Sí ({{ $syllabus->visado_at->format('d/m/Y H:i') }})</span>
                            @else
                                <span class="text-red-500">✗ No</span>
                            @endif
                        </div>
                        <div><span class="text-gray-500">Archivo:</span> <strong>{{ $syllabus->filename }}</strong></div>
                    </div>

                    @if($syllabus->visas->isNotEmpty())
                        <div class="border-t pt-4">
                            <h3 class="font-semibold mb-3">Historial de Visados</h3>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Visor</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Estado</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Observaciones</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($syllabus->visas as $visa)
                                        <tr>
                                            <td class="px-4 py-2 text-sm">{{ $visa->visor->name }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $visa->status }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $visa->observations ?? '—' }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $visa->visado_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
