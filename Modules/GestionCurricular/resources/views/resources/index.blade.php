<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-end gap-3">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Gestión de Recursos</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Recursos Académicos</h2>
                <p class="text-slate-500">Solicita y gestiona recursos bibliográficos, hemerográficos y equipamiento.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('resources.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Nueva Solicitud
                </a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div class="p-4">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <select name="status" onchange="this.form.submit()" aria-label="Filtrar por estado" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los estados</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="in_process" {{ request('status') === 'in_process' ? 'selected' : '' }}>En Proceso</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completado</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rechazado</option>
                            </select>
                        </div>
                        <div>
                            <select name="request_type" onchange="this.form.submit()" aria-label="Filtrar por tipo" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los tipos</option>
                                <option value="bibliographic" {{ request('request_type') === 'bibliographic' ? 'selected' : '' }}>Bibliográfico</option>
                                <option value="hemerographic" {{ request('request_type') === 'hemerographic' ? 'selected' : '' }}>Hemerográfico</option>
                                <option value="equipment" {{ request('request_type') === 'equipment' ? 'selected' : '' }}>Equipamiento / Otros</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(count(request()->except(['page'])) > 0)
                                <a href="{{ route('resources.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors" title="Quitar filtros">
                                    <span class="material-symbols-outlined text-lg">filter_alt_off</span>
                                    Limpiar
                                </a>
                            @endif
                            <p class="text-xs text-slate-400">Los filtros se aplican automáticamente</p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($requests as $req)
                    @php
                        $statusColors = [
                            'pending' => ['bg' => 'bg-amber-500/10', 'border' => 'border-amber-500/20', 'text' => 'text-amber-600'],
                            'in_process' => ['bg' => 'bg-blue-500/10', 'border' => 'border-blue-500/20', 'text' => 'text-blue-600'],
                            'completed' => ['bg' => 'bg-emerald-500/10', 'border' => 'border-emerald-500/20', 'text' => 'text-emerald-600'],
                            'rejected' => ['bg' => 'bg-red-500/10', 'border' => 'border-red-500/20', 'text' => 'text-red-600'],
                        ];
                        $cardColors = [
                            'pending' => 'bg-accent/10 text-navy',
                            'in_process' => 'bg-blue-600 text-white',
                            'completed' => 'bg-emerald-700 text-white',
                            'rejected' => 'bg-red-700 text-white',
                        ];
                        $statusLabels = [
                            'pending' => 'Pendiente',
                            'in_process' => 'En Proceso',
                            'completed' => 'Completado',
                            'rejected' => 'Rechazado',
                        ];
                        $typeLabels = [
                            'bibliographic' => 'Bibliográfico',
                            'hemerographic' => 'Hemerográfico',
                            'equipment' => 'Equipamiento',
                        ];
                        $sc = $statusColors[$req->status] ?? $statusColors['pending'];
                        $cc = $cardColors[$req->status] ?? $cardColors['pending'];
                    @endphp
                    <div class="bg-white rounded-xl border border-outline-variant/40 overflow-hidden hover:shadow-lg transition-all group cursor-pointer flex flex-col h-full">
                        <div class="h-32 p-4 flex flex-col justify-between {{ $cc }}">
                            <span class="self-start text-[9px] font-black px-2 py-0.5 rounded border {{ $sc['bg'] }} {{ $sc['border'] }} {{ $sc['text'] }}">
                                {{ $statusLabels[$req->status] ?? $req->status }}
                            </span>
                            <div>
                                <p class="text-[9px] font-bold opacity-70">{{ $req->code }}</p>
                                <h3 class="text-lg font-bold line-clamp-1">{{ $req->title }}</h3>
                            </div>
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-navy border border-outline-variant">
                                    {{ strtoupper(substr($req->applicant?->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-navy">{{ $req->applicant?->name }}</p>
                                    <p class="text-[9px] text-slate-400 uppercase font-bold">Solicitante</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-slate-500 mb-4">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">category</span>
                                    {{ $typeLabels[$req->request_type] ?? $req->request_type }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">event</span>
                                    {{ $req->academicPeriod?->name }}
                                </span>
                            </div>
                            <div class="mt-auto pt-4 border-t border-outline-variant/30 flex justify-between items-center">
                                <span class="text-[10px] text-slate-400 italic">{{ $req->created_at->diffForHumans() }}</span>
                                <a href="{{ route('resources.show', $req) }}" title="Ver detalle" aria-label="Ver detalle de la solicitud" class="text-navy hover:bg-navy/5 p-1 rounded transition-colors">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full border-2 border-dashed border-outline-variant/40 rounded-xl p-6 flex flex-col items-center justify-center text-center gap-4">
                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                            <span class="material-symbols-outlined text-3xl">inventory_2</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-600">No hay solicitudes</p>
                            <p class="text-xs text-slate-400">Crea la primera solicitud de recursos</p>
                        </div>
                    </div>
                @endforelse

                <!-- <a href="{{ route('resources.create') }}" class="border-2 border-dashed border-outline-variant/40 rounded-xl p-6 flex flex-col items-center justify-center text-center gap-4 hover:bg-navy/5 transition-all cursor-pointer">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                        <span class="material-symbols-outlined text-3xl">add</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-600">Nueva Solicitud</p>
                        <p class="text-xs text-slate-400">Crear solicitud de recurso</p>
                    </div>
                </a> -->
            </div>

            <div class="mt-6">
                {{ $requests->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
