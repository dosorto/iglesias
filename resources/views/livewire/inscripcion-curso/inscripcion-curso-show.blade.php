<div class="space-y-6">

{{-- HEADER --}}
<div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600
dark:from-indigo-700 dark:to-violet-700 shadow-md px-6 py-5">

<div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
<div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>

<div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

<div class="flex items-center gap-3">

<div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">

<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
</svg>

</div>

<div>

<h1 class="text-xl font-bold text-white leading-tight">
Detalle de Inscripción
</h1>

<p class="text-indigo-100 text-sm mt-0.5">
{{ $inscripcion->feligres->persona->nombre_completo ?? '' }}
</p>

</div>
</div>

<div class="flex items-center gap-2">

@can('inscripcion-curso.edit')
<a href="{{ route('inscripcion-curso.edit',$inscripcion) }}"
class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
bg-white/15 hover:bg-white/25 border border-white/20
text-white text-sm font-medium transition-all duration-150">
Editar
</a>
@endcan

<a href="{{ route('inscripcion-curso.index') }}"
class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
bg-white/15 hover:bg-white/25 border border-white/20
text-white text-sm font-medium transition-all duration-150">
Volver
</a>

</div>
</div>
</div>



<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

{{-- INFORMACION --}}
<div class="lg:col-span-1 space-y-5">

<div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
ring-1 ring-black/5 dark:ring-white/5 overflow-hidden">

<div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
<h2 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
Información de Inscripción
</h2>
</div>

<div class="p-6 space-y-5">

{{-- FELIGRES --}}
<div>

<p class="text-xs font-semibold text-gray-500 uppercase mb-1">
Feligrés
</p>

<p class="text-base font-bold text-gray-900 dark:text-white">
{{ $inscripcion->feligres->persona->nombre_completo ?? '—' }}
</p>

<p class="text-xs text-gray-500 mt-0.5">
DNI: {{ $inscripcion->feligres->persona->dni ?? '—' }}
</p>

</div>


{{-- CURSO --}}
<div>

<p class="text-xs font-semibold text-gray-500 uppercase mb-1">
Curso
</p>

<p class="text-sm font-medium text-gray-900 dark:text-white">
{{ $inscripcion->curso->nombre ?? '—' }}
</p>

</div>


{{-- INSTRUCTOR --}}
<div>

<p class="text-xs font-semibold text-gray-500 uppercase mb-1">
Instructor
</p>

<p class="text-sm font-medium text-gray-900 dark:text-white">
{{ $inscripcion->curso?->instructors?->pluck('feligres.persona.nombre_completo')?->filter()?->join(', ') ?: ($inscripcion->curso?->instructor?->feligres?->persona?->nombre_completo ?? '—') }}
</p>

</div>


{{-- FECHA INSCRIPCION --}}
<div>

<p class="text-xs font-semibold text-gray-500 uppercase mb-1">
Fecha de inscripción
</p>

<p class="text-sm text-gray-900 dark:text-white">
{{ $inscripcion->fecha_inscripcion ?? '—' }}
</p>

</div>


{{-- APROBADO --}}
<div>

<p class="text-xs font-semibold text-gray-500 uppercase mb-1">
Aprobado
</p>

<span class="px-2 py-1 text-xs rounded font-semibold
{{ $inscripcion->aprobado
? 'bg-green-100 text-green-700'
: 'bg-red-100 text-red-700' }}">

{{ $inscripcion->aprobado ? 'Sí' : 'No' }}

</span>

</div>


{{-- CERTIFICADO --}}
<div>

<p class="text-xs font-semibold text-gray-500 uppercase mb-1">
Certificado emitido
</p>

<span class="px-2 py-1 text-xs rounded font-semibold
{{ $inscripcion->certificado_emitido
? 'bg-blue-100 text-blue-700'
: 'bg-gray-100 text-gray-700' }}">

{{ $inscripcion->certificado_emitido ? 'Emitido' : 'No emitido' }}

</span>

</div>


{{-- FECHA CERTIFICADO --}}
<div>

<p class="text-xs font-semibold text-gray-500 uppercase mb-1">
Fecha certificado
</p>

<p class="text-sm text-gray-900 dark:text-white">
{{ $inscripcion->fecha_certificado ?? '—' }}
</p>

</div>

</div>


<div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700/50">

<div class="text-[10px] text-gray-400 flex flex-col gap-0.5">

<span>
Creado: {{ $inscripcion->created_at->format('d/m/Y H:i') }}
</span>

<span>
Actualizado: {{ $inscripcion->updated_at->format('d/m/Y H:i') }}
</span>

</div>

</div>

</div>

</div>



{{-- HISTORIAL --}}
<div class="lg:col-span-2">

<div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
ring-1 ring-black/5 dark:ring-white/5">

<div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
<h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">
Historial de Cambios
</h2>
</div>

<div class="p-6">

<ul class="-mb-8">

@forelse ($inscripcion->auditLogs as $log)

<li>

<div class="relative pb-8">

<div class="relative flex space-x-3">

<div>

<span class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center">

<svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
d="M15.232 5.232l3.536 3.536"/>
</svg>

</span>

</div>

<div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">

<div>
<p class="text-sm text-gray-500">
{{ $log->event }}
</p>
</div>

<div class="text-right text-xs text-gray-500">
<time>
{{ $log->created_at->format('d/m/y H:i') }}
</time>
</div>

</div>

</div>

</div>

</li>

@empty

<li class="flex flex-col items-center justify-center py-10 text-center">

<p class="text-sm text-gray-400 italic">
No se han registrado movimientos.
</p>

</li>

@endforelse

</ul>

</div>

</div>

</div>

</div>

</div>