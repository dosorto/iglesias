<?php

use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\PersonaController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->middleware('verified')
        ->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    Route::middleware('permission:users.view')
        ->get('/users', fn () => view('users.index'))
        ->name('users.index');

    Route::middleware('permission:users.create')
        ->get('/users/create', fn () => view('users.create'))
        ->name('users.create');

    Route::middleware('permission:users.edit')
        ->get('/users/{user}/edit', fn (User $user) => view('users.edit', compact('user')))
        ->name('users.edit');

    Route::middleware('permission:roles.view')
        ->get('/roles', fn () => view('roles.index'))
        ->name('roles.index');

    Route::middleware('permission:roles.create')
        ->get('/roles/create', fn () => view('roles.create'))
        ->name('roles.create');

    Route::middleware('permission:roles.edit')
        ->get('/roles/{role}/edit', fn (Role $role) => view('roles.edit', compact('role')))
        ->name('roles.edit');

    Route::middleware('permission:roles.view')
        ->get('/settings', fn () => view('settings.index'))
        ->name('settings.index');

    Route::middleware('permission:audit.view')
        ->get('/audit', fn () => view('admin.audit'))
        ->name('audit.index');


    // Personas CRUD
    Route::middleware('permission:personas.view')
        ->get('/personas', [PersonaController::class, 'index'])
        ->name('personas.index');

    Route::middleware('permission:personas.create')
        ->get('/personas/create', [PersonaController::class, 'create'])
        ->name('personas.create');

    Route::middleware('permission:personas.create')
        ->post('/personas', [PersonaController::class, 'store'])
        ->name('personas.store');

    Route::middleware('permission:personas.view')
        ->get('/personas/{persona}', [PersonaController::class, 'show'])
        ->name('personas.show');

    Route::middleware('permission:personas.edit')
        ->get('/personas/{persona}/edit', [PersonaController::class, 'edit'])
        ->name('personas.edit');

    Route::middleware('permission:personas.edit')
        ->put('/personas/{persona}', [PersonaController::class, 'update'])
        ->name('personas.update');

    Route::middleware('permission:personas.delete')
        ->delete('/personas/{persona}', [PersonaController::class, 'destroy'])
        ->name('personas.destroy');

    // Estudiantes
    Route::middleware('permission:estudiantes.view')
        ->get('/estudiantes', [EstudianteController::class, 'index'])
        ->name('estudiantes.index');


    // Religion
        Route::middleware('permission:religion.view')
        ->get('/religion', [\App\Http\Controllers\ReligionController::class, 'index'])
        ->name('religion.index');   

    Route::middleware('permission:religion.create')
        ->get('/religion/create', [\App\Http\Controllers\ReligionController::class, 'create'])
        ->name('religion.create');

    Route::middleware('permission:religion.create')
        ->post('/religion', [\App\Http\Controllers\ReligionController::class, 'store'])
        ->name('religion.store');

    Route::middleware('permission:religion.view')
        ->get('/religion/{religion}', [\App\Http\Controllers\ReligionController::class, 'show'])
        ->name('religion.show');

    Route::middleware('permission:religion.edit')
        ->get('/religion/{religion}/edit', [\App\Http\Controllers\ReligionController::class, 'edit'])
        ->name('religion.edit');

    Route::middleware('permission:religion.edit')
        ->put('/religion/{religion}', [\App\Http\Controllers\ReligionController::class, 'update'])
        ->name('religion.update');

    Route::middleware('permission:religion.delete')
        ->delete('/religion/{religion}', [\App\Http\Controllers\ReligionController::class, 'destroy'])
        ->name('religion.destroy');
});

require __DIR__.'/auth.php';
