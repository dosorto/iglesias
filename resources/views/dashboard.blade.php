@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h5 class="text-sm text-gray-500">Usuarios</h5>
            <p class="text-3xl font-bold">{{ \App\Models\User::count() }}</p>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h5 class="text-sm text-gray-500">Roles</h5>
            <p class="text-3xl font-bold">{{ \Spatie\Permission\Models\Role::count() }}</p>
        </div>
    </div>
@endsection
