<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Revisión Curricular') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <span class="text-sm text-gray-500">Carrera:</span>
                            <p class="font-medium">{{ $review->career->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Periodo:</span>
                            <p class="font-medium">{{ $review->academicPeriod->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Plantilla:</span>
                            <p class="font-medium">{{ $review->checklistTemplate->code }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Acción Curricular:</span>
                            <p class="font-medium">{{ $review->actionType?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Revisor:</span>
                            <p class="font-medium">{{ $review->reviewer->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Estado:</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $review->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $review->status === 'completed' ? 'Completado' : 'Borrador' }}
                            </span>
                        </div>
                    </div>

                    @if($review->evaluations->isNotEmpty())
                        <h3 class="font-semibold mb-3">Resultados de Evaluación</h3>
                        <table class="min-w-full divide-y divide-gray-200 mb-6">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Criterio</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Puntaje</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($review->evaluations as $evaluation)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $evaluation->criterion->code }} - {{ \Illuminate\Support\Str::limit($evaluation->criterion->description, 60) }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $evaluation->score }}/5</td>
                                        <td class="px-4 py-2 text-sm">{{ $evaluation->observations ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    @if($review->observations)
                        <div class="mb-4">
                            <span class="text-sm text-gray-500">Observaciones Generales:</span>
                            <p class="mt-1">{{ $review->observations }}</p>
                        </div>
                    @endif

                    @if($review->status === 'completed' && $review->technicalReport)
                        <div class="mt-4 pt-4 border-t">
                            <a href="{{ route('curriculum.reports.show', $review->technicalReport) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-500">
                                Ver Informe Técnico
                            </a>
                        </div>
                    @elseif($review->status === 'completed')
                        <div class="mt-4 pt-4 border-t">
                            <a href="{{ route('curriculum.reports.create', $review) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-500">
                                Generar Informe Técnico
                            </a>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('curriculum.reviews.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
