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
    // Iglesias CRUD
    Route::middleware('permission:iglesias.view')
        ->get('/iglesias', [\App\Http\Controllers\IglesiaController::class, 'index'])
        ->name('iglesias.index');

    Route::middleware('permission:iglesias.create')
        ->get('/iglesias/create', [\App\Http\Controllers\IglesiaController::class, 'create'])
        ->name('iglesias.create');

    Route::middleware('permission:iglesias.create')
        ->post('/iglesias', [\App\Http\Controllers\IglesiaController::class, 'store'])
        ->name('iglesias.store');

    Route::middleware('permission:iglesias.view')
        ->get('/iglesias/{iglesia}', [\App\Http\Controllers\IglesiaController::class, 'show'])
        ->name('iglesias.show');

    Route::middleware('permission:iglesias.edit')
        ->get('/iglesias/{iglesia}/edit', [\App\Http\Controllers\IglesiaController::class, 'edit'])
        ->name('iglesias.edit');

    Route::middleware('permission:iglesias.edit')
        ->put('/iglesias/{iglesia}', [\App\Http\Controllers\IglesiaController::class, 'update'])
        ->name('iglesias.update');

    Route::middleware('permission:iglesias.delete')
        ->delete('/iglesias/{iglesia}', [\App\Http\Controllers\IglesiaController::class, 'destroy'])
        ->name('iglesias.destroy');    


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

    // TipoCurso CRUD
    Route::middleware('permission:tipocurso.view')
        ->get('/tipocurso', [\App\Http\Controllers\TipoCursoController::class, 'index'])
        ->name('tipocurso.index');

    Route::middleware('permission:tipocurso.create')
        ->get('/tipocurso/create', [\App\Http\Controllers\TipoCursoController::class, 'create'])
        ->name('tipocurso.create');

    Route::middleware('permission:tipocurso.create')
        ->post('/tipocurso', [\App\Http\Controllers\TipoCursoController::class, 'store'])
        ->name('tipocurso.store');

    Route::middleware('permission:tipocurso.view')
        ->get('/tipocurso/{tipocurso}', [\App\Http\Controllers\TipoCursoController::class, 'show'])
        ->name('tipocurso.show');

    Route::middleware('permission:tipocurso.edit')
        ->get('/tipocurso/{tipocurso}/edit', [\App\Http\Controllers\TipoCursoController::class, 'edit'])
        ->name('tipocurso.edit');

    Route::middleware('permission:tipocurso.edit')
        ->put('/tipocurso/{tipocurso}', [\App\Http\Controllers\TipoCursoController::class, 'update'])
        ->name('tipocurso.update');

    Route::middleware('permission:tipocurso.delete')
        ->delete('/tipocurso/{tipocurso}', [\App\Http\Controllers\TipoCursoController::class, 'destroy'])
        ->name('tipocurso.destroy');
});

require __DIR__.'/auth.php';
