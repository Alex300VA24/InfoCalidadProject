<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $resourceRequest->code }} - {{ $resourceRequest->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div><span class="text-gray-500">Código:</span> <strong>{{ $resourceRequest->code }}</strong></div>
                        <div><span class="text-gray-500">Estado:</span>
                            @switch($resourceRequest->status)
                                @case('pending') <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pendiente</span> @break
                                @case('in_process') <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">En Proceso</span> @break
                                @case('completed') <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Completado</span> @break
                                @case('rejected') <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rechazado</span> @break
                            @endswitch
                        </div>
                        <div><span class="text-gray-500">Tipo:</span> <strong>{{ ucfirst($resourceRequest->request_type) }}</strong></div>
                        <div><span class="text-gray-500">Periodo:</span> <strong>{{ $resourceRequest->academicPeriod->name }}</strong></div>
                        <div><span class="text-gray-500">Solicitante:</span> <strong>{{ $resourceRequest->applicant->name }}</strong></div>
                        <div><span class="text-gray-500">Fecha:</span> <strong>{{ $resourceRequest->created_at->format('d/m/Y') }}</strong></div>
                    </div>

                    @if($resourceRequest->description)
                        <div class="border-t pt-4">
                            <h3 class="font-semibold mb-2">Descripción</h3>
                            <p class="text-sm">{{ $resourceRequest->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($resourceRequest->documents->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="font-semibold mb-3">Documentos</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tipo</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">N° Documento</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Asunto</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Archivo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($resourceRequest->documents as $doc)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $doc->document_type === 'petition' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $doc->document_type === 'petition' ? 'Petición' : 'Respuesta' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm">{{ $doc->document_number ?? '—' }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $doc->subject ?? '—' }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <a href="{{ route('resources.documents.download', $doc) }}" class="text-blue-600 hover:text-blue-900">Descargar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($resourceRequest->attachments->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="font-semibold mb-3">Anexos / Evidencias</h3>
                        <ul class="divide-y divide-gray-200">
                            @foreach($resourceRequest->attachments as $att)
                                <li class="py-2 flex justify-between items-center">
                                    <span class="text-sm">{{ $att->filename }}</span>
                                    @if($att->description)
                                        <span class="text-xs text-gray-500">{{ $att->description }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Agregar documento de respuesta --}}
            @if($resourceRequest->status !== 'completed' && auth()->user()->isSecretaria())
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold mb-4">Agregar Documento de Respuesta</h3>
                        <form method="POST" action="{{ route('resources.add-response', $resourceRequest) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">N° de Documento</label>
                                    <input type="text" name="document_number" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Asunto</label>
                                    <input type="text" name="subject" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 text-sm">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Archivo PDF</label>
                                <input type="file" name="file" accept=".pdf" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                    Adjuntar Respuesta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
