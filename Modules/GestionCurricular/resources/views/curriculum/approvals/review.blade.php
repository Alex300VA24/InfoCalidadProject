<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Revisar Informe Técnico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-6">
                        <div><span class="text-gray-500">Carrera:</span> <strong>{{ $report->curriculumReview->career->name }}</strong></div>
                        <div><span class="text-gray-500">Periodo:</span> <strong>{{ $report->curriculumReview->academicPeriod->name }}</strong></div>
                        <div><span class="text-gray-500">Acción Curricular:</span> <strong>{{ $report->curriculumReview->actionType->name }}</strong></div>
                        <div><span class="text-gray-500">Preparado por:</span> <strong>{{ $report->preparer->name }}</strong></div>
                    </div>

                    <div class="prose max-w-none mb-6">
                        {!! nl2br(e($report->content)) !!}
                    </div>

                    @if($report->curriculumReview->evaluations->isNotEmpty())
                        <h3 class="font-semibold mb-3">Evaluación de Lista de Cotejo</h3>
                        <table class="min-w-full divide-y divide-gray-200 mb-6">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Criterio</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 w-16">Puntaje</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($report->curriculumReview->evaluations as $eval)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $eval->criterion->description }}</td>
                                        <td class="px-4 py-2 text-sm text-center">{{ $eval->score }}/5</td>
                                        <td class="px-4 py-2 text-sm">{{ $eval->observations ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    @if(!$report->approval)
                        <div class="border-t pt-6">
                            <h3 class="font-semibold mb-4">Emitir Dictamen</h3>
                            <form method="POST" action="{{ route('curriculum.approvals.approve', $report) }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Decisión</label>
                                    <div class="flex space-x-6">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="decision" value="approved" required class="mr-2">
                                            <span class="text-green-700 font-medium">Aprobar</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="decision" value="observed" required class="mr-2">
                                            <span class="text-red-700 font-medium">Observar / Rechazar</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Comentarios</label>
                                    <textarea name="comments" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300" placeholder="Ingrese sus comentarios o justificación..."></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <a href="{{ route('curriculum.approvals.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2">Cancelar</a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                        Emitir Dictamen
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="border-t pt-6">
                            <h3 class="font-semibold mb-2">Dictamen Emitido</h3>
                            <p>Decisión: <strong class="{{ $report->approval->decision === 'approved' ? 'text-green-600' : 'text-red-600' }}">{{ $report->approval->decision === 'approved' ? 'APROBADO' : 'OBSERVADO' }}</strong></p>
                            @if($report->approval->comments)
                                <p class="text-sm mt-1">Comentarios: {{ $report->approval->comments }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
