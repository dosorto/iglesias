@extends('layouts.app')

@section('content')

<div class="space-y-6">

{{-- HEADER --}}
<div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600
            dark:from-indigo-700 dark:to-violet-700 shadow-md px-6 py-5">

    <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
    <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>

    <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

        <div class="flex items-center gap-3">

            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                ✓
            </div>

            <div>
                <h1 class="text-xl font-bold text-white">
                    Registrar Nueva Inscripción
                </h1>

                <p class="text-indigo-100 text-sm">
                    Inscribir a {{ $instructor->feligres->persona->nombre_completo }}
                </p>
            </div>

        </div>

        <a href="{{ route('instructor.show',$instructor) }}"
           class="px-4 py-2 bg-white/20 text-white rounded-lg">
            Volver
        </a>

    </div>

</div>


{{-- FORMULARIO LIVEWIRE --}}
<livewire:inscripcion-curso.inscripcion-curso-create 
    :feligresId="$instructor->feligres->id"
/>

</div>

@endsection