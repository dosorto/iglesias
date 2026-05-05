@extends('layouts.app')

@section('content')

@php
    use App\Models\Bautismo;
    use App\Models\Confirmacion;
    use App\Models\Feligres;
    use App\Models\InscripcionCurso;
    use App\Models\Matrimonio;
    use App\Models\PrimeraComunion;
    use Illuminate\Support\Carbon;

    $now = Carbon::now();
    $months = collect(range(5, 0))->map(function (int $offset) use ($now) {
        $month = $now->copy()->subMonths($offset);

        return [
            'month' => (int) $month->month,
            'year' => (int) $month->year,
            'label' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'][$month->month - 1],
        ];
    });

    $countByMonth = function (string $modelClass, string $dateColumn, int $year, int $month): int {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();

        return $modelClass::query()
            ->whereBetween($dateColumn, [$start, $end])
            ->count();
    };

    $activityRows = $months->map(function (array $item) use ($countByMonth) {
        $bautismos = $countByMonth(Bautismo::class, 'fecha_bautismo', $item['year'], $item['month']);
        $matrimonios = $countByMonth(Matrimonio::class, 'fecha_matrimonio', $item['year'], $item['month']);
        $confirmaciones = $countByMonth(Confirmacion::class, 'fecha_confirmacion', $item['year'], $item['month']);
        $comuniones = $countByMonth(PrimeraComunion::class, 'fecha_primera_comunion', $item['year'], $item['month']);

        return [
            'label' => $item['label'],
            'total' => $bautismos + $matrimonios + $confirmaciones + $comuniones,
            'bautismos' => $bautismos,
            'matrimonios' => $matrimonios,
            'confirmaciones' => $confirmaciones,
            'comuniones' => $comuniones,
        ];
    });

    $maxActivity = max(1, (int) $activityRows->max('total'));
    $activityRows = $activityRows->map(function (array $row) use ($maxActivity) {
        $height = max(12, (int) round(($row['total'] / $maxActivity) * 100));
        $row['height'] = $height;

        $row['height_class'] = match (true) {
            $height <= 15 => 'h-8',
            $height <= 25 => 'h-12',
            $height <= 35 => 'h-16',
            $height <= 45 => 'h-20',
            $height <= 55 => 'h-24',
            $height <= 65 => 'h-28',
            $height <= 75 => 'h-32',
            $height <= 85 => 'h-36',
            default => 'h-40',
        };

        return $row;
    });

    $year = (int) $now->year;
    $feligresCount = Feligres::count();
    $bautismoYearCount = Bautismo::query()->whereYear('fecha_bautismo', $year)->count();
    $matrimonioYearCount = Matrimonio::query()->whereYear('fecha_matrimonio', $year)->count();
    $cursoCount = InscripcionCurso::count();
    $confirmacionYearCount = Confirmacion::query()->whereYear('fecha_confirmacion', $year)->count();
    $comunionYearCount = PrimeraComunion::query()->whereYear('fecha_primera_comunion', $year)->count();
    $recentFeligreses = Feligres::with('persona')->latest()->take(6)->get();
@endphp

{{-- ENCABEZADO --}}
<div class="mb-8">
    <h1 class="text-4xl font-serif text-[var(--color-purpura-sagrado)] dark:text-white">Bienvenido al Archivo Sagrado</h1>
    <p class="text-slate-600 dark:text-gray-300 mt-2 max-w-2xl italic text-sm">Gestión administrativa y sacramental de tu parroquia con una visión clara y ordenada.</p>
</div>

{{-- ACCIONES RÁPIDAS --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('feligres.create') }}"
       class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-[var(--color-verde-esperanza)] transition-all group">
        <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-emerald-700 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">Nuevo feligrés</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Añadir al censo</p>
        </div>
    </a>

    <a href="{{ route('bautismo.index') }}"
       class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-[var(--color-azul-mariano)] transition-all group">
        <div class="w-9 h-9 rounded-lg bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-sky-700 dark:text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">Certificado digital</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Emitir fe de bautismo</p>
        </div>
    </a>

    <a href="{{ route('matrimonio.create') }}"
       class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-[var(--color-rojo-martir)] transition-all group">
        <div class="w-9 h-9 rounded-lg bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-rose-700 dark:text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">Nuevo matrimonio</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Registrar acta</p>
        </div>
    </a>

    <a href="{{ route('inscripcion-curso.create') }}"
       class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-[var(--color-dorado-divino)] transition-all group">
        <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-700 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">Inscribir curso</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Catequesis y más</p>
        </div>
    </a>
</div>

{{-- MÉTRICAS --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 border-b-4 border-b-emerald-400">
        <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-emerald-700 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <p class="text-3xl font-serif font-bold text-gray-900 dark:text-white">{{ \App\Models\Feligres::count() }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Feligreses activos</p>
    </div>

    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 border-b-4 border-b-sky-400">
        <div class="w-9 h-9 rounded-lg bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
            </svg>
        </div>
        <p class="text-3xl font-serif font-bold text-gray-900 dark:text-white">{{ $bautismoYearCount }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Bautismos (año)</p>
    </div>

    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 border-b-4 border-b-rose-400">
        <div class="w-9 h-9 rounded-lg bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <p class="text-3xl font-serif font-bold text-gray-900 dark:text-white">{{ $matrimonioYearCount }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Matrimonios</p>
    </div>

    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 border-b-4 border-b-amber-400">
        <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-amber-700 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-5.747-8.12l11.494 4.373M6.253 14.373l11.494-4.373"/>
            </svg>
        </div>
        <p class="text-3xl font-serif font-bold text-gray-900 dark:text-white">{{ $cursoCount }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Inscritos en cursos</p>
    </div>
</div>

{{-- GRID PRINCIPAL --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- COLUMNA IZQUIERDA --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- GRÁFICO --}}
        <section class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-serif text-gray-900 dark:text-white">Actividad sacramental</h2>
                <div class="flex items-center gap-2 text-xs">
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">Últimos 6 meses</span>
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 font-semibold">{{ $activityRows->sum('total') }} registros</span>
                </div>
            </div>
            <div class="h-52 flex items-end gap-3 px-2">
                @forelse($activityRows as $row)
                    <div class="flex-1 {{ $row['height_class'] }} bg-gradient-to-t from-emerald-700 to-emerald-400 rounded-t-lg relative group transition-all hover:from-emerald-800 hover:to-emerald-500"
                         title="{{ $row['label'] }}: {{ $row['total'] }}">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 text-[11px] font-bold text-emerald-700 opacity-0 group-hover:opacity-100 bg-white/90 border border-emerald-200 rounded-md px-2 py-0.5">
                            {{ $row['total'] }}
                        </div>
                    </div>
                @empty
                    <div class="w-full h-full flex items-center justify-center text-sm text-gray-400">
                        Sin actividad sacramental registrada.
                    </div>
                @endforelse
            </div>
            <div class="flex justify-between mt-3 px-2 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                @foreach($activityRows as $row)
                    <span>{{ $row['label'] }}</span>
                @endforeach
            </div>
        </section>

        {{-- INGRESOS RECIENTES --}}
        <section class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-serif text-gray-900 dark:text-white mb-5">Ingresos recientes</h2>
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Nueva acta de matrimonio</p>
                        <p class="text-xs text-gray-400">Hace 2 horas · Folio #452</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Registro feligrés: Elena Solís</p>
                        <p class="text-xs text-gray-400">Hace 4 horas · ID: 1209</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Bautismo registrado</p>
                        <p class="text-xs text-gray-400">Hace 1 día · Folio #891</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- COLUMNA DERECHA --}}
    <div class="space-y-6">

        {{-- NUEVOS FELIGRESES --}}
        <section class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-serif text-gray-900 dark:text-white">Nuevos feligreses</h2>
                <a href="{{ route('feligres.create') }}"
                   class="w-8 h-8 rounded-lg bg-emerald-700 flex items-center justify-center text-white hover:bg-emerald-800 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>
            </div>
            <div class="space-y-2">
                @forelse($recentFeligreses as $feligres)
                <a href="{{ route('feligres.show', $feligres->id) }}"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                    <div class="w-9 h-9 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 text-emerald-700 dark:text-emerald-300 font-semibold text-xs group-hover:bg-emerald-700 group-hover:text-white transition-all">
                        {{ strtoupper(substr($feligres->persona->primer_nombre ?? 'F', 0, 1)) }}{{ strtoupper(substr($feligres->persona->primer_apellido ?? '', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                            {{ $feligres->persona->primer_nombre ?? 'Sin nombre' }} {{ $feligres->persona->primer_apellido ?? '' }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $feligres->created_at?->format('d M') ?? '—' }}</p>
                    </div>
                </a>
                @empty
                <p class="text-sm text-center text-gray-400 py-6">No hay feligreses nuevos</p>
                @endforelse
            </div>
            <a href="{{ route('feligres.index') }}"
               class="block mt-5 py-2.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-emerald-400 hover:text-emerald-700 transition-all">
                Ver todos los registros
            </a>
        </section>

        {{-- SACRAMENTOS --}}
        <section class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-serif text-gray-900 dark:text-white mb-5">Sacramentos</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-sky-500 flex-shrink-0"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Bautismo</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $bautismoYearCount }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-rose-500 flex-shrink-0"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Matrimonio</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $matrimonioYearCount }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 flex-shrink-0"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Confirmación</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $confirmacionYearCount }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500 flex-shrink-0"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Comunión</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $comunionYearCount }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500 flex-shrink-0"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Cursos</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $cursoCount }}</span>
                </div>
            </div>
        </section>

    </div>
</div>

@endsection