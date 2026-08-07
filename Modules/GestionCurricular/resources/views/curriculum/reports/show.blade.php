<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Informe Técnico') }}
            </h2>
            <div class="flex space-x-2">
                @if($report->status === 'draft')
                    <a href="{{ route('curriculum.reports.edit', $report) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 text-white rounded-md text-sm hover:bg-yellow-400">Editar</a>
                    <form method="POST" action="{{ route('curriculum.reports.finalize', $report) }}" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('¿Finalizar y enviar para aprobación?')" class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-md text-sm hover:bg-green-500">
                            Finalizar y Enviar
                        </button>
                    </form>
                @endif
                <a href="{{ route('curriculum.reports.pdf', $report) }}" class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded-md text-sm hover:bg-red-500">PDF</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div><span class="text-gray-500">Carrera:</span> <strong>{{ $report->curriculumReview->career->name }}</strong></div>
                        <div><span class="text-gray-500">Periodo:</span> <strong>{{ $report->curriculumReview->academicPeriod->name }}</strong></div>
                        <div><span class="text-gray-500">Acción Curricular:</span> <strong>{{ $report->curriculumReview->actionType->name }}</strong></div>
                        <div>
                            <span class="text-gray-500">Estado:</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $report->status === 'finalized' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $report->status === 'finalized' ? 'Finalizado' : 'Borrador' }}
                            </span>
                        </div>
                        <div><span class="text-gray-500">Preparado por:</span> <strong>{{ $report->preparer->name }}</strong></div>
                        <div><span class="text-gray-500">Fecha:</span> <strong>{{ $report->created_at->format('d/m/Y') }}</strong></div>
                    </div>

                    <div class="prose max-w-none">
                        {!! nl2br(e($report->content)) !!}
                    </div>

                    @if($report->approval)
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="font-semibold mb-2">Dictamen del Director de Escuela</h3>
                            <p class="text-sm">
                                Decisión: <span class="font-medium {{ $report->approval->decision === 'approved' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $report->approval->decision === 'approved' ? 'APROBADO' : 'OBSERVADO' }}
                                </span>
                            </p>
                            @if($report->approval->comments)
                                <p class="text-sm mt-2">Comentarios: {{ $report->approval->comments }}</p>
                            @endif
                            <p class="text-sm text-gray-500 mt-1">Aprobado el: {{ $report->approval->approved_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
