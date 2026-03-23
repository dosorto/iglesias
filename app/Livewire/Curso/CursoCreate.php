<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use App\Models\Curso;
use App\Models\Encargado;
use App\Models\TipoCurso;
use App\Models\Instructor;
use App\Models\Persona;
use App\Models\Feligres;
use App\Models\TenantIglesia;
use Illuminate\Support\Facades\Auth;

class CursoCreate extends Component
{
    public int $paso = 1;

    public $nombre = '';
    public $fecha_inicio = null;
    public $fecha_fin = null;
    public $estado = 'Activo';
    public $encargado_id = null;

    public $tipo_curso_id = null;
    public $instructor_id = null;

    // =========================
    // SOPORTE IGLESIA
    // =========================
    public $iglesia_id = null;

    // =========================
    // MINI FORM TIPO CURSO
    // =========================
    public bool $showCrearTipoCurso = false;
    public string $nuevo_tipo_nombre = '';
    public string $nuevo_tipo_descripcion = '';

    // =========================
    // MINI FORM INSTRUCTOR
    // =========================
    public bool $showCrearInstructor = false;
    public string $instructor_busqueda = '';
    public string $instructor_estado = 'idle'; // idle, found, sin_feligres, sin_persona, multiples

    public ?array $personaInstructor = null;
    public $feligresInstructorId = null;
    public array $resultadosBusqueda = [];

    // Datos de nueva persona
    public string $i_dni = '';
    public string $i_primer_nombre = '';
    public string $i_segundo_nombre = '';
    public string $i_primer_apellido = '';
    public string $i_segundo_apellido = '';
    public string $i_telefono = '';
    public string $i_email = '';
    public string $i_fecha_nacimiento = '';
    public string $i_sexo = '';

    public function mount(): void
    {
        $this->iglesia_id = TenantIglesia::currentId();

        $encargado = Encargado::with('feligres')->first();

        if ($encargado) {
            $this->encargado_id = $encargado->id;
            $this->iglesia_id = $encargado->feligres?->id_iglesia ?: $this->iglesia_id;
        }
    }

    public function siguientePaso(): void
    {
        if ($this->paso === 1) {
            $this->validate([
                'nombre' => ['required', 'max:200', 'regex:/[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ]/'],
                'fecha_inicio' => ['required', 'date'],
                'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
                'estado' => ['required', 'in:Activo,Finalizado,Cancelado'],
            ], [
                'nombre.required' => 'El nombre del curso es obligatorio.',
                'nombre.max' => 'El nombre del curso no puede exceder 200 caracteres.',
                'nombre.regex' => 'El nombre del curso debe contener al menos una letra.',

                'fecha_inicio.required' => 'Debe seleccionar la fecha de inicio.',
                'fecha_inicio.date' => 'La fecha de inicio no es válida.',

                'fecha_fin.date' => 'La fecha de fin no es válida.',
                'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',

                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'El estado seleccionado no es válido.',
            ]);
        }

        if ($this->paso === 2) {
            $this->validate([
                'tipo_curso_id' => ['required', 'exists:tipos_curso,id'],
            ], [
                'tipo_curso_id.required' => 'Debe seleccionar un tipo de curso.',
                'tipo_curso_id.exists' => 'El tipo de curso seleccionado no existe.',
            ]);
        }

        if ($this->paso < 3) {
            $this->paso++;
        }
    }

    public function anteriorPaso(): void
    {
        if ($this->paso > 1) {
            $this->paso--;
        }
    }

    // =========================================================
    // TIPO CURSO
    // =========================================================
    public function abrirCrearTipoCurso(): void
    {
        $this->resetValidation([
            'nuevo_tipo_nombre',
            'nuevo_tipo_descripcion',
        ]);

        $this->showCrearTipoCurso = true;
    }

    public function cancelarCrearTipoCurso(): void
    {
        $this->resetValidation([
            'nuevo_tipo_nombre',
            'nuevo_tipo_descripcion',
        ]);

        $this->showCrearTipoCurso = false;
        $this->nuevo_tipo_nombre = '';
        $this->nuevo_tipo_descripcion = '';
    }

    public function guardarNuevoTipoCurso(): void
    {
        $this->validate([
            'nuevo_tipo_nombre' => ['required', 'string', 'min:3', 'max:150', 'unique:tipos_curso,nombre_curso'],
            'nuevo_tipo_descripcion' => ['nullable', 'string', 'max:65535'],
        ], [
            'nuevo_tipo_nombre.required' => 'El nombre del tipo de curso es obligatorio.',
            'nuevo_tipo_nombre.min' => 'El nombre del tipo de curso debe tener al menos 3 caracteres.',
            'nuevo_tipo_nombre.max' => 'El nombre del tipo de curso no puede exceder 150 caracteres.',
            'nuevo_tipo_nombre.unique' => 'Ese tipo de curso ya existe.',
            'nuevo_tipo_descripcion.max' => 'La descripción es demasiado larga.',
        ]);

        $tipo = TipoCurso::create([
            'nombre_curso' => trim($this->nuevo_tipo_nombre),
            'descripcion_curso' => trim($this->nuevo_tipo_descripcion) ?: null,
        ]);

        $this->tipo_curso_id = $tipo->id;
        $this->showCrearTipoCurso = false;
        $this->nuevo_tipo_nombre = '';
        $this->nuevo_tipo_descripcion = '';

        session()->flash('success', 'Tipo de curso creado correctamente.');
    }

    // =========================================================
    // INSTRUCTOR
    // =========================================================
    public function abrirCrearInstructor(): void
    {
        $this->showCrearInstructor = true;
        $this->instructor_busqueda = '';
        $this->instructor_estado = 'idle';
        $this->personaInstructor = null;
        $this->feligresInstructorId = null;
        $this->resultadosBusqueda = [];

        $this->limpiarMiniInstructor();
        $this->resetValidation();
    }

    public function cancelarCrearInstructor(): void
    {
        $this->showCrearInstructor = false;
        $this->instructor_busqueda = '';
        $this->instructor_estado = 'idle';
        $this->personaInstructor = null;
        $this->feligresInstructorId = null;
        $this->resultadosBusqueda = [];

        $this->limpiarMiniInstructor();
        $this->resetValidation();
    }

    public function limpiarMiniInstructor(): void
    {
        $this->i_dni = '';
        $this->i_primer_nombre = '';
        $this->i_segundo_nombre = '';
        $this->i_primer_apellido = '';
        $this->i_segundo_apellido = '';
        $this->i_telefono = '';
        $this->i_email = '';
        $this->i_fecha_nacimiento = '';
        $this->i_sexo = '';
    }

    public function buscarPersonaInstructor(): void
    {
        $this->resetValidation([
            'instructor_busqueda',
            'i_dni',
            'i_primer_nombre',
            'i_primer_apellido',
            'iglesia_id',
        ]);

        $this->personaInstructor = null;
        $this->feligresInstructorId = null;
        $this->resultadosBusqueda = [];

        $valor = trim($this->instructor_busqueda);

        if ($valor === '') {
            $this->addError('instructor_busqueda', 'Debes escribir un DNI o nombre para buscar.');
            return;
        }

        $query = Persona::query();

        if (preg_match('/^[0-9]+$/', $valor)) {
            $query->where('dni', $valor);
        } else {
            $query->where(function ($q) use ($valor) {
                $q->where('primer_nombre', 'like', "%{$valor}%")
                    ->orWhere('segundo_nombre', 'like', "%{$valor}%")
                    ->orWhere('primer_apellido', 'like', "%{$valor}%")
                    ->orWhere('segundo_apellido', 'like', "%{$valor}%");
            });
        }

        $personas = $query->limit(10)->get();

        if ($personas->isEmpty()) {
            $this->instructor_estado = 'sin_persona';
            $this->limpiarMiniInstructor();

            if (preg_match('/^[0-9]+$/', $valor)) {
                $this->i_dni = $valor;
            }

            return;
        }

        if ($personas->count() === 1) {
            $this->seleccionarPersonaInstructor($personas->first()->id);
            return;
        }

        $this->instructor_estado = 'multiples';
        $this->resultadosBusqueda = $personas->map(function ($persona) {
            return [
                'id' => $persona->id,
                'dni' => $persona->dni,
                'nombre_completo' => $persona->nombre_completo,
                'telefono' => $persona->telefono,
                'email' => $persona->email,
            ];
        })->toArray();
    }

    public function seleccionarPersonaInstructor(int $id): void
    {
        $persona = Persona::findOrFail($id);

        $this->personaInstructor = [
            'id' => $persona->id,
            'dni' => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono' => $persona->telefono,
            'email' => $persona->email,
        ];

        $this->resultadosBusqueda = [];

        $feligres = Feligres::where('id_persona', $persona->id)->first();

        if (! $feligres) {
            $this->instructor_estado = 'sin_feligres';
            $this->feligresInstructorId = null;
            return;
        }

        $instructor = Instructor::where('feligres_id', $feligres->id)->first();

        if ($instructor) {
            $this->instructor_id = $instructor->id;
            $this->cancelarCrearInstructor();

            session()->flash('success', 'Ese instructor ya existe y fue seleccionado automáticamente.');
            return;
        }

        $this->instructor_estado = 'found';
        $this->feligresInstructorId = $feligres->id;
    }

    public function guardarMiniInstructorPersona(): void
    {
        $this->validate([
            'iglesia_id' => ['required', 'exists:iglesias,id'],

            'i_dni' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/^[0-9]+$/',
                'unique:personas,dni',
            ],

            'i_primer_nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u',
            ],

            'i_segundo_nombre' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u',
            ],

            'i_primer_apellido' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u',
            ],

            'i_segundo_apellido' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u',
            ],

            'i_telefono' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
            ],

            'i_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'i_fecha_nacimiento' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'i_sexo' => [
                'required',
                'in:M,F',
            ],
        ], [
            'iglesia_id.required' => 'No se pudo determinar la iglesia para registrar el instructor.',
            'iglesia_id.exists' => 'La iglesia seleccionada no existe.',

            'i_dni.required' => 'El DNI es obligatorio.',
            'i_dni.min' => 'El DNI debe tener al menos 8 caracteres.',
            'i_dni.max' => 'El DNI no puede exceder 20 caracteres.',
            'i_dni.regex' => 'El DNI solo debe contener números.',
            'i_dni.unique' => 'Ya existe una persona registrada con ese DNI.',

            'i_primer_nombre.required' => 'El primer nombre es obligatorio.',
            'i_primer_nombre.max' => 'El primer nombre no puede exceder 255 caracteres.',
            'i_primer_nombre.regex' => 'El primer nombre solo debe contener letras y espacios.',

            'i_segundo_nombre.max' => 'El segundo nombre no puede exceder 255 caracteres.',
            'i_segundo_nombre.regex' => 'El segundo nombre solo debe contener letras y espacios.',

            'i_primer_apellido.required' => 'El primer apellido es obligatorio.',
            'i_primer_apellido.max' => 'El primer apellido no puede exceder 255 caracteres.',
            'i_primer_apellido.regex' => 'El primer apellido solo debe contener letras y espacios.',

            'i_segundo_apellido.max' => 'El segundo apellido no puede exceder 255 caracteres.',
            'i_segundo_apellido.regex' => 'El segundo apellido solo debe contener letras y espacios.',

            'i_telefono.required' => 'El teléfono es obligatorio.',
            'i_telefono.max' => 'El teléfono no puede exceder 20 caracteres.',
            'i_telefono.regex' => 'El teléfono solo debe contener números.',

            'i_email.email' => 'El correo electrónico no es válido.',
            'i_email.max' => 'El correo electrónico no puede exceder 255 caracteres.',

            'i_fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'i_fecha_nacimiento.date' => 'La fecha de nacimiento no es válida.',
            'i_fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento debe ser hoy o una fecha anterior.',

            'i_sexo.required' => 'El sexo es obligatorio.',
            'i_sexo.in' => 'El sexo seleccionado no es válido.',
        ]);

        $persona = Persona::create([
            'dni' => trim($this->i_dni),
            'primer_nombre' => trim($this->i_primer_nombre),
            'segundo_nombre' => trim($this->i_segundo_nombre) ?: null,
            'primer_apellido' => trim($this->i_primer_apellido),
            'segundo_apellido' => trim($this->i_segundo_apellido) ?: null,
            'telefono' => trim($this->i_telefono),
            'email' => trim($this->i_email) ?: null,
            'fecha_nacimiento' => $this->i_fecha_nacimiento,
            'sexo' => $this->i_sexo,
        ]);

        $feligres = Feligres::create([
            'id_persona' => $persona->id,
            'id_iglesia' => $this->iglesia_id,
            'fecha_ingreso' => now()->toDateString(),
            'estado' => 'Activo',
        ]);

        $instructor = Instructor::create([
            'feligres_id' => $feligres->id,
            'fecha_ingreso' => now()->toDateString(),
            'estado' => 'Activo',
            'created_by' => Auth::id(),
        ]);

        $this->instructor_id = $instructor->id;
        $this->cancelarCrearInstructor();

        session()->flash('success', 'Instructor creado correctamente.');
    }

    public function guardarInstructorDesdePersonaExistente(): void
    {
        if (! $this->personaInstructor || empty($this->personaInstructor['id'])) {
            $this->addError('instructor_busqueda', 'No hay una persona válida seleccionada.');
            return;
        }

        if (! $this->iglesia_id) {
            $this->addError('iglesia_id', 'No se pudo determinar la iglesia para registrar el instructor.');
            return;
        }

        $feligres = Feligres::where('id_persona', $this->personaInstructor['id'])->first();

        if (! $feligres) {
            $feligres = Feligres::create([
                'id_persona' => $this->personaInstructor['id'],
                'id_iglesia' => $this->iglesia_id,
                'fecha_ingreso' => now()->toDateString(),
                'estado' => 'Activo',
            ]);
        }

        $instructorExistente = Instructor::where('feligres_id', $feligres->id)->first();

        if ($instructorExistente) {
            $this->instructor_id = $instructorExistente->id;
            $this->cancelarCrearInstructor();

            session()->flash('success', 'Ese instructor ya existía y fue seleccionado.');
            return;
        }

        $instructor = Instructor::create([
            'feligres_id' => $feligres->id,
            'fecha_ingreso' => now()->toDateString(),
            'estado' => 'Activo',
            'created_by' => Auth::id(),
        ]);

        $this->instructor_id = $instructor->id;
        $this->cancelarCrearInstructor();

        session()->flash('success', 'Instructor registrado correctamente.');
    }

    public function guardar()
    {
        $this->validate([
            'nombre' => ['required', 'max:200', 'regex:/[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ]/'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:Activo,Finalizado,Cancelado'],
            'iglesia_id' => ['required', 'exists:iglesias,id'],
            'encargado_id' => ['required', 'exists:encargado,id'],
            'tipo_curso_id' => ['required', 'exists:tipos_curso,id'],
            'instructor_id' => ['required', 'exists:instructores,id'],
        ], [
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'nombre.max' => 'El nombre del curso no puede exceder 200 caracteres.',
            'nombre.regex' => 'El nombre del curso debe contener al menos una letra.',

            'fecha_inicio.required' => 'Debe seleccionar la fecha de inicio.',
            'fecha_inicio.date' => 'La fecha de inicio no es válida.',

            'fecha_fin.date' => 'La fecha de fin no es válida.',
            'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',

            'iglesia_id.required' => 'No se pudo determinar la iglesia del curso.',
            'iglesia_id.exists' => 'La iglesia seleccionada no existe.',

            'encargado_id.required' => 'Debe existir un encargado para registrar el curso.',
            'encargado_id.exists' => 'El encargado seleccionado no existe.',

            'tipo_curso_id.required' => 'Debe seleccionar un tipo de curso.',
            'tipo_curso_id.exists' => 'El tipo de curso seleccionado no existe.',

            'instructor_id.required' => 'Debe seleccionar un instructor.',
            'instructor_id.exists' => 'El instructor seleccionado no existe.',
        ]);

        Curso::create([
            'nombre' => $this->nombre,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estado,
            'iglesia_id' => $this->iglesia_id,
            'encargado_id' => $this->encargado_id,
            'tipo_curso_id' => $this->tipo_curso_id,
            'instructor_id' => $this->instructor_id,
            'created_by' => Auth::id(),
        ]);

        session()->flash('success', 'Curso creado correctamente.');

        return redirect()->route('curso.index');
    }

    public function render()
    {
        return view('livewire.curso.curso-create', [
            'tipos' => TipoCurso::orderBy('nombre_curso')->get(),
            'instructores' => Instructor::with('feligres.persona')->orderBy('id', 'desc')->get(),
            'encargados' => Encargado::with('feligres.persona')->get(),
        ]);
    }
}