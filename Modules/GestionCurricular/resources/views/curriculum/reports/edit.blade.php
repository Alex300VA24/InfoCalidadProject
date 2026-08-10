<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Informe Técnico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('curriculum.reports.update', $report) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contenido del Informe Técnico</label>
                            <textarea name="content" rows="20" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 font-mono text-sm" required>{{ old('content', $report->content) }}</textarea>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('curriculum.reports.show', $report) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
