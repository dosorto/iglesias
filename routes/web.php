<?php

use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\PersonaController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Livewire\Volt\Volt;
use App\Livewire\Curso\MatriculadoCursoShow;

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

    Route::middleware('permission:bautismo.view|matrimonio.view|confirmacion.view|primera-comunion.view')
        ->get('/sacramentos', fn () => view('sacramentos.index'))
        ->name('sacramentos.index');

    Route::middleware('permission:roles.view')
        ->get('/configuracion/certificado-bautismo', fn () => view('configuracion.certificado-bautismo'))
        ->name('configuracion.certificado-bautismo');

    Route::middleware('permission:roles.view')
        ->get('/configuracion/iglesia', [\App\Http\Controllers\IglesiaController::class, 'editConfiguracion'])
        ->name('configuracion.iglesia.edit');

    Route::middleware('permission:roles.view')
        ->put('/configuracion/iglesia', [\App\Http\Controllers\IglesiaController::class, 'updateConfiguracion'])
        ->name('configuracion.iglesia.update');

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

    Route::middleware('permission:inscripcion-curso.view')
        ->get('/matriculado-curso/{inscripcionCurso}', [\App\Http\Controllers\InscripcionCursoController::class, 'matricula'])
        ->name('matriculado-curso.show');

    Route::middleware('permission:inscripcion-curso.view')
    ->get('/matriculado-curso/{inscripcionId}', function ($inscripcionId) {return view('curso.matricula', compact('inscripcionId'));})
    ->name('matriculado-curso.show');

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

    Route::middleware('permission:bautismo.view')
        ->get('/bautismo/{bautismo}/certificado/pdf', [\App\Http\Controllers\BautismoController::class, 'certificadoPdf'])
        ->name('bautismo.certificado.pdf');

    // Matrimonio CRUD
    Route::middleware('permission:matrimonio.view')
        ->get('/matrimonio', [\App\Http\Controllers\MatrimonioController::class, 'index'])
        ->name('matrimonio.index');

    Route::middleware('permission:matrimonio.create')
        ->get('/matrimonio/create', [\App\Http\Controllers\MatrimonioController::class, 'create'])
        ->name('matrimonio.create');

    Route::middleware('permission:matrimonio.view')
        ->get('/matrimonio/{matrimonio}', [\App\Http\Controllers\MatrimonioController::class, 'show'])
        ->name('matrimonio.show');

    Route::middleware('permission:matrimonio.edit')
        ->get('/matrimonio/{matrimonio}/edit', [\App\Http\Controllers\MatrimonioController::class, 'edit'])
        ->name('matrimonio.edit');

    Route::middleware('permission:matrimonio.view')
        ->get('/matrimonio/{matrimonio}/certificado/pdf', [\App\Http\Controllers\MatrimonioController::class, 'certificadoPdf'])
        ->name('matrimonio.certificado.pdf');

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

        // PrimeraComunion CRUD
    Route::middleware('permission:primera-comunion.view')
        ->get('/primera-comunion', [\App\Http\Controllers\PrimeraComunionController::class, 'index'])
        ->name('primera-comunion.index');

    Route::middleware('permission:primera-comunion.create')
        ->get('/primera-comunion/create', [\App\Http\Controllers\PrimeraComunionController::class, 'create'])
        ->name('primera-comunion.create');

    Route::middleware('permission:primera-comunion.create')
        ->post('/primera-comunion', [\App\Http\Controllers\PrimeraComunionController::class, 'store'])
        ->name('primera-comunion.store');

    Route::middleware('permission:primera-comunion.view')
        ->get('/primera-comunion/{primeraComunion}', [\App\Http\Controllers\PrimeraComunionController::class, 'show'])
        ->name('primera-comunion.show');

    Route::middleware('permission:primera-comunion.edit')
        ->get('/primera-comunion/{primeraComunion}/edit', [\App\Http\Controllers\PrimeraComunionController::class, 'edit'])
        ->name('primera-comunion.edit');

    Route::middleware('permission:primera-comunion.edit')
        ->put('/primera-comunion/{primeraComunion}', [\App\Http\Controllers\PrimeraComunionController::class, 'update'])
        ->name('primera-comunion.update');

    Route::middleware('permission:primera-comunion.delete')
        ->delete('/primera-comunion/{primeraComunion}', [\App\Http\Controllers\PrimeraComunionController::class, 'destroy'])
        ->name('primera-comunion.destroy');

    Route::middleware('permission:primera-comunion.view')
    ->get('/primera-comunion/{primeraComunion}/certificado/pdf', [\App\Http\Controllers\PrimeraComunionController::class, 'certificadoPdf'])
    ->name('primera-comunion.certificado.pdf');

    // Curso CRUD
    Route::middleware('permission:curso.view')
        ->get('/curso', [\App\Http\Controllers\CursoController::class, 'index'])
        ->name('curso.index');

    Route::middleware('permission:curso.create')
        ->get('/curso/create', [\App\Http\Controllers\CursoController::class, 'create'])
        ->name('curso.create');

    Route::middleware('permission:curso.create')
        ->post('/curso', [\App\Http\Controllers\CursoController::class, 'store'])
        ->name('curso.store');

    Route::middleware('permission:curso.view')
        ->get('/curso/{curso}', [\App\Http\Controllers\CursoController::class, 'show'])
        ->name('curso.show');

    Route::middleware('permission:curso.edit')
        ->get('/curso/{curso}/edit', [\App\Http\Controllers\CursoController::class, 'edit'])
        ->name('curso.edit');

    Route::middleware('permission:curso.edit')
        ->put('/curso/{curso}', [\App\Http\Controllers\CursoController::class, 'update'])
        ->name('curso.update');

    Route::middleware('permission:curso.delete')
        ->delete('/curso/{curso}', [\App\Http\Controllers\CursoController::class, 'destroy'])
        ->name('curso.destroy');

    // Inscripcion curso
    Route::middleware('permission:inscripcion-curso.view')
        ->get('/inscripcion-curso', [\App\Http\Controllers\InscripcionCursoController::class, 'index'])
        ->name('inscripcion-curso.index');

    Route::middleware('permission:inscripcion-curso.create')
            ->get('/inscripcion-curso/create', [\App\Http\Controllers\InscripcionCursoController::class, 'create'])
            ->name('inscripcion-curso.create');

    Route::middleware('permission:inscripcion-curso.create')
            ->post('/inscripcion-curso', [\App\Http\Controllers\InscripcionCursoController::class, 'store'])
            ->name('inscripcion-curso.store');

    Route::middleware('permission:inscripcion-curso.view')
            ->get('/inscripcion-curso/{inscripcionCurso}', [\App\Http\Controllers\InscripcionCursoController::class, 'show'])
            ->name('inscripcion-curso.show');

    Route::middleware('permission:inscripcion-curso.edit')
            ->get('/inscripcion-curso/{inscripcionCurso}/edit', [\App\Http\Controllers\InscripcionCursoController::class, 'edit'])
            ->name('inscripcion-curso.edit');

    Route::get('/inscripcion-curso/{inscripcion}/edit', [\App\Http\Controllers\InscripcionCursoController::class, 'edit'])
        ->name('inscripcion-curso.edit');

    Route::middleware('permission:inscripcion-curso.delete')
            ->delete('/inscripcion-curso/{inscripcionCurso}', [\App\Http\Controllers\InscripcionCursoController::class, 'destroy'])
            ->name('inscripcion-curso.destroy');

    Route::middleware('permission:inscripcion-curso.create')
    ->get('/instructor/{instructor}/inscripcion-curso/create', [\App\Http\Controllers\InscripcionCursoController::class, 'createFromInstructor'])
    ->name('instructor.inscripcion.create');

    
    Route::middleware('permission:iglesias.logo')
    ->get('/iglesia/logo', \App\Livewire\Iglesia\IglesiaLogoUpdate::class)
    ->name('iglesia.logo');

    // Confirmacion 
    Route::middleware('permission:confirmacion.view')
        ->get('/confirmacion', [\App\Http\Controllers\ConfirmacionController::class, 'index'])
        ->name('confirmacion.index');

    Route::middleware('permission:confirmacion.create')
        ->get('/confirmacion/create', [\App\Http\Controllers\ConfirmacionController::class, 'create'])
        ->name('confirmacion.create');

    Route::middleware('permission:confirmacion.view')
        ->get('/confirmacion/{confirmacion}', [\App\Http\Controllers\ConfirmacionController::class, 'show'])
        ->name('confirmacion.show');

    Route::middleware('permission:confirmacion.edit')
        ->get('/confirmacion/{confirmacion}/edit', [\App\Http\Controllers\ConfirmacionController::class, 'edit'])
        ->name('confirmacion.edit');

    Route::middleware('permission:confirmacion.view')
        ->get('/confirmacion/{confirmacion}/certificado/pdf', [\App\Http\Controllers\ConfirmacionController::class, 'certificadoPdf'])
        ->name('confirmacion.certificado.pdf');


    Route::middleware('permission:inscripcion-curso.view')
        ->get('/inscripcion-curso/{inscripcion}/certificado/pdf', [\App\Http\Controllers\InscripcionCursoController::class, 'certificadoPdf'])
        ->name('inscripcion-curso.certificado.pdf');
});

require __DIR__.'/auth.php';
