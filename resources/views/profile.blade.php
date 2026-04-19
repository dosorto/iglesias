@extends('layouts.app')

@section('content')
    <div class="mb-6 sm:mb-8">
        <h2 class="font-semibold text-2xl text-gray-900 dark:text-gray-100 leading-tight">
            Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            Administra tu información de acceso y seguridad.
        </p>
    </div>

    <div class="pb-10">
        <div class="content-container max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <div class="p-5 sm:p-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
                    <livewire:profile.update-profile-information-form />
                </div>

                <div class="p-5 sm:p-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-5 sm:p-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
@endsection
