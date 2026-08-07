<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Aprobaciones de Informes Técnicos') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Carrera</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periodo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Preparado por</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($reports as $report)
                                <tr>
                                    <td class="px-6 py-4 text-sm">{{ $report->curriculumReview->career->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $report->curriculumReview->academicPeriod->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $report->curriculumReview->actionType->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $report->preparer->name }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($report->approval)
                                            <span class="px-2 py-1 text-xs rounded-full {{ $report->approval->decision === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $report->approval->decision === 'approved' ? 'Aprobado' : 'Observado' }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('curriculum.approvals.review', $report) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $report->approval ? 'Ver' : 'Revisar' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay informes pendientes de aprobación</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $reports->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
