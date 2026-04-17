@extends('layouts.app')

@section('title', 'Configuracion de Empresa')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configuracion de Empresa</h1>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Administra el nombre y el logo global que se muestran en el panel de test.</p>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('configuracion.empresa.update') }}" enctype="multipart/form-data"
          class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nombre de empresa</label>
            <input id="company_name" name="company_name" type="text"
                   value="{{ old('company_name', $setting->company_name) }}"
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
            @error('company_name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="company_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Logo de empresa</label>
            <input id="company_logo" name="company_logo" type="file" accept="image/png,image/jpeg,image/webp"
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Formatos permitidos: JPG, PNG, WEBP. Maximo 2 MB.</p>
            @error('company_logo')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-900/40">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">Vista previa actual</p>
            <div class="flex items-center gap-4">
                <img src="{{ $setting->company_logo_url ?: asset('image/Logo_guest.png') }}"
                     alt="Logo empresa" class="h-14 w-14 rounded-lg object-contain bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700" />
                <div>
                    <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $setting->company_name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Se muestra en sidebar global (modo test)</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('settings.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors font-medium">
                Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection
