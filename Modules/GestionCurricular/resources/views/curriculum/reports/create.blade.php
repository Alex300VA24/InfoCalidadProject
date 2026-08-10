<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generar Informe Técnico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="font-semibold mb-2">Resumen de la Revisión</h3>
                    <p class="text-sm text-gray-600">
                        Carrera: <strong>{{ $review->career->name }}</strong> |
                        Periodo: <strong>{{ $review->academicPeriod->name }}</strong> |
                        Acción: <strong>{{ $review->actionType->name }}</strong>
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('curriculum.reports.store', $review) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contenido del Informe Técnico</label>
                            <p class="text-xs text-gray-500 mb-2">Incluya las observaciones de la lista de cotejo, justificación de la acción curricular seleccionada y recomendaciones.</p>
                            <textarea name="content" rows="20" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 font-mono text-sm" required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('curriculum.reviews.show', $review) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                                Generar Informe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
