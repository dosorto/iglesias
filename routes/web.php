<?php

use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\PersonaController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Livewire\Volt\Volt;

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

    Volt::route('register-perfil', 'pages.auth.register-perfil')->name('register-perfil');

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

    // Feligreses CRUD
    Route::middleware('permission:feligres.view')
        ->get('/feligres', [\App\Http\Controllers\FeligresController::class, 'index'])
        ->name('feligres.index');

    Route::middleware('permission:feligres.create')
        ->get('/feligres/create', [\App\Http\Controllers\FeligresController::class, 'create'])
        ->name('feligres.create');

    Route::middleware('permission:feligres.create')
        ->post('/feligres', [\App\Http\Controllers\FeligresController::class, 'store'])
        ->name('feligres.store');

    Route::middleware('permission:feligres.view')
        ->get('/feligres/{feligre}', [\App\Http\Controllers\FeligresController::class, 'show'])
        ->name('feligres.show');

    Route::middleware('permission:feligres.edit')
        ->get('/feligres/{feligre}/edit', [\App\Http\Controllers\FeligresController::class, 'edit'])
        ->name('feligres.edit');

    Route::middleware('permission:feligres.edit')
        ->put('/feligres/{feligre}', [\App\Http\Controllers\FeligresController::class, 'update'])
        ->name('feligres.update');

    Route::middleware('permission:feligres.delete')
        ->delete('/feligres/{feligre}', [\App\Http\Controllers\FeligresController::class, 'destroy'])
        ->name('feligres.destroy');

    // Encargados CRUD
    Route::middleware('permission:encargado.view')
        ->get('/encargado', [\App\Http\Controllers\EncargadoController::class, 'index'])
        ->name('encargado.index');

    Route::middleware('permission:encargado.create')
        ->get('/encargado/create', [\App\Http\Controllers\EncargadoController::class, 'create'])
        ->name('encargado.create');

    Route::middleware('permission:encargado.create')
        ->post('/encargado', [\App\Http\Controllers\EncargadoController::class, 'store'])
        ->name('encargado.store');

    Route::middleware('permission:encargado.view')
        ->get('/encargado/{encargado}', [\App\Http\Controllers\EncargadoController::class, 'show'])
        ->name('encargado.show');

    Route::middleware('permission:encargado.edit')
        ->get('/encargado/{encargado}/edit', [\App\Http\Controllers\EncargadoController::class, 'edit'])
        ->name('encargado.edit');

    Route::middleware('permission:encargado.edit')
        ->put('/encargado/{encargado}', [\App\Http\Controllers\EncargadoController::class, 'update'])
        ->name('encargado.update');

    Route::middleware('permission:encargado.delete')
        ->delete('/encargado/{encargado}', [\App\Http\Controllers\EncargadoController::class, 'destroy'])
        ->name('encargado.destroy');

    // Instructores CRUD
    Route::middleware('permission:instructor.view')
        ->get('/instructor', [\App\Http\Controllers\InstructorController::class, 'index'])
        ->name('instructor.index');

    Route::middleware('permission:instructor.create')
        ->get('/instructor/create', [\App\Http\Controllers\InstructorController::class, 'create'])
        ->name('instructor.create');

    Route::middleware('permission:instructor.create')
        ->post('/instructor', [\App\Http\Controllers\InstructorController::class, 'store'])
        ->name('instructor.store');

    Route::middleware('permission:instructor.view')
        ->get('/instructor/{instructor}', [\App\Http\Controllers\InstructorController::class, 'show'])
        ->name('instructor.show');

    Route::middleware('permission:instructor.edit')
        ->get('/instructor/{instructor}/edit', [\App\Http\Controllers\InstructorController::class, 'edit'])
        ->name('instructor.edit');

    Route::middleware('permission:instructor.edit')
        ->put('/instructor/{instructor}', [\App\Http\Controllers\InstructorController::class, 'update'])
        ->name('instructor.update');

    Route::middleware('permission:instructor.delete')
        ->delete('/instructor/{instructor}', [\App\Http\Controllers\InstructorController::class, 'destroy'])
        ->name('instructor.destroy');

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

    // Bautismo CRUD
    Route::middleware('permission:bautismo.view')
        ->get('/bautismo', [\App\Http\Controllers\BautismoController::class, 'index'])
        ->name('bautismo.index');

    Route::middleware('permission:bautismo.create')
        ->get('/bautismo/create', [\App\Http\Controllers\BautismoController::class, 'create'])
        ->name('bautismo.create');

    Route::middleware('permission:bautismo.view')
        ->get('/bautismo/{bautismo}', [\App\Http\Controllers\BautismoController::class, 'show'])
        ->name('bautismo.show');

    Route::middleware('permission:bautismo.edit')
        ->get('/bautismo/{bautismo}/edit', [\App\Http\Controllers\BautismoController::class, 'edit'])
        ->name('bautismo.edit');

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

    // Cursos 
    Route::middleware('permission:curso.view')
    ->get('/curso', [\App\Http\Controllers\CursoController::class, 'index'])
    ->name('curso.index');

    Route::middleware('permission:curso.create')
        ->get('/curso/create', [\App\Http\Controllers\CursoController::class, 'create'])
        ->name('curso.create');

    Route::middleware('permission:curso.view')
        ->get('/curso/{curso}', [\App\Http\Controllers\CursoController::class, 'show'])
        ->name('curso.show');

    Route::middleware('permission:curso.edit')
        ->get('/curso/{curso}/edit', [\App\Http\Controllers\CursoController::class, 'edit'])
        ->name('curso.edit');
});

require __DIR__.'/auth.php';
