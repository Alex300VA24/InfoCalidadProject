<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-end gap-3">
            <div>
                <span class="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Repositorio Institucional</span>
                <h2 class="text-3xl font-bold text-navy mt-2">Repositorio de Sílabos</h2>
                <p class="text-slate-500">Gestiona y valida el contenido académico de las carreras.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('syllabi.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span class="material-symbols-outlined text-lg">upload_file</span>
                    Subir Sílabo
                </a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div class="p-4">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <select name="career_id" onchange="this.form.submit()" aria-label="Filtrar por carrera" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todas las carreras</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}" {{ request('career_id') == $career->id ? 'selected' : '' }}>{{ $career->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="academic_period_id" onchange="this.form.submit()" aria-label="Filtrar por periodo" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los periodos</option>
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}" {{ request('academic_period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="teacher_id" onchange="this.form.submit()" aria-label="Filtrar por docente" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los docentes</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="is_visado" onchange="this.form.submit()" aria-label="Filtrar por estado" class="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los estados</option>
                                <option value="yes" {{ request('is_visado') === 'yes' ? 'selected' : '' }}>Visados</option>
                                <option value="no" {{ request('is_visado') === 'no' ? 'selected' : '' }}>No visados</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(count(request()->except(['page'])) > 0)
                                <a href="{{ route('syllabi.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors" title="Quitar filtros">
                                    <span class="material-symbols-outlined text-lg">filter_alt_off</span>
                                    Limpiar
                                </a>
                            @endif
                            <p class="text-xs text-slate-400">Los filtros se aplican automáticamente</p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($syllabi as $syllabus)
                    <div class="bg-white rounded-xl border border-outline-variant/40 overflow-hidden hover:shadow-lg transition-all group cursor-pointer flex flex-col h-full">
                        <div class="h-32 p-4 flex flex-col justify-between {{ $syllabus->is_visado ? 'bg-navy text-white' : 'bg-accent/10 text-navy' }}">
                            <span class="self-start text-[9px] font-black px-2 py-0.5 rounded border {{ $syllabus->is_visado ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300' : 'bg-amber-500/10 border-amber-500/20 text-amber-600' }}">
                                VISA: {{ $syllabus->is_visado ? 'APROBADO' : 'PENDIENTE' }}
                            </span>
                            <div>
                                <p class="text-[9px] font-bold opacity-70">{{ $syllabus->subject?->code }}</p>
                                <h3 class="text-lg font-bold line-clamp-1">{{ $syllabus->subject?->name }}</h3>
                            </div>
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-navy border border-outline-variant">
                                    {{ strtoupper(substr($syllabus->teacher?->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-navy">{{ $syllabus->teacher?->name }}</p>
                                    <p class="text-[9px] text-slate-400 uppercase font-bold">{{ $syllabus->career?->code }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-slate-500 mb-4">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">event</span>
                                    {{ $syllabus->academicPeriod?->name }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">description</span>
                                    {{ number_format($syllabus->file_size / 1024, 1) }} KB
                                </span>
                            </div>
                            <div class="mt-auto pt-4 border-t border-outline-variant/30 flex justify-between items-center">
                                <span class="text-[10px] text-slate-400 italic">{{ $syllabus->created_at->diffForHumans() }}</span>
                                <div class="flex gap-1">
                                    <a href="{{ route('syllabi.show', $syllabus) }}" title="Ver detalle" aria-label="Ver detalle del sílabo" class="text-navy hover:bg-navy/5 p-1 rounded transition-colors">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                    <a data-turbo="false" href="{{ route('syllabi.download', $syllabus) }}" title="Descargar" aria-label="Descargar sílabo" class="text-navy hover:bg-navy/5 p-1 rounded transition-colors">
                                        <span class="material-symbols-outlined text-lg">download</span>
                                    </a>
                                    @if(!$syllabus->is_visado)
                                        <form method="POST" action="{{ route('syllabi.visa', $syllabus) }}" class="inline">
                                            @csrf
                                            <button type="submit" title="Marcar como visado" aria-label="Marcar sílabo como visado" class="text-emerald-600 hover:bg-emerald-50 p-1 rounded transition-colors">
                                                <span class="material-symbols-outlined text-lg">verified</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full border-2 border-dashed border-outline-variant/40 rounded-xl p-6 flex flex-col items-center justify-center text-center gap-4">
                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                            <span class="material-symbols-outlined text-3xl">folder_off</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-600">No hay sílabos</p>
                            <p class="text-xs text-slate-400">Sube el primer sílabo al repositorio</p>
                        </div>
                    </div>
                @endforelse

                <!-- <a href="{{ route('syllabi.create') }}" class="border-2 border-dashed border-outline-variant/40 rounded-xl p-6 flex flex-col items-center justify-center text-center gap-4 hover:bg-navy/5 transition-all cursor-pointer">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                        <span class="material-symbols-outlined text-3xl">add</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-600">Nuevo Sílabo</p>
                        <p class="text-xs text-slate-400">Agregar al repositorio</p>
                    </div>
                </a> -->
            </div>

            <div class="mt-6">
                {{ $syllabi->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
