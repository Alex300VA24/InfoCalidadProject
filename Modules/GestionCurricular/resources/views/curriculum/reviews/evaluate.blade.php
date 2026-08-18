<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Evaluar Lista de Cotejo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ $review->checklistTemplate->code }} - {{ $review->checklistTemplate->name }}</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Carrera: <strong>{{ $review->career->name }}</strong> |
                        Periodo: <strong>{{ $review->academicPeriod->name }}</strong>
                    </p>

                    <form method="POST" action="{{ route('curriculum.reviews.save-evaluation', $review) }}">
                        @csrf
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Criterio</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-24">Puntaje (0-5)</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($review->checklistTemplate->criteria as $criterion)
                                    @php $eval = $review->evaluations->firstWhere('criterion_id', $criterion->id); @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium">{{ $criterion->code }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $criterion->description }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="number" name="scores[{{ $criterion->id }}]" value="{{ $eval->score ?? 0 }}"
                                                   min="0" max="5" required
                                                   class="w-16 text-center rounded-md border-gray-300 shadow-sm focus:border-indigo-300">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" name="observations[{{ $criterion->id }}]" value="{{ $eval->observations ?? '' }}"
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 text-sm">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                Guardar Evaluación
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($review->evaluations->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Completar Revisión - Seleccionar Tipo de Acción Curricular</h3>
                        <form method="POST" action="{{ route('curriculum.reviews.complete', $review) }}">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Acción Curricular</label>
                                @foreach($actionTypes as $action)
                                    <div class="flex items-center mb-2">
                                        <input type="radio" name="action_type_id" value="{{ $action->id }}" required class="mr-2">
                                        <div>
                                            <span class="font-medium">{{ $action->name }}</span>
                                            <p class="text-xs text-gray-500">{{ $action->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Observaciones Generales</label>
                                <textarea name="observations" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                    Completar Revisión
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
