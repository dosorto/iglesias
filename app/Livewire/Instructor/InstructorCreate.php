<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use App\Models\Persona;
use App\Models\Instructor;
use App\Models\Feligres;
use App\Models\TenantIglesia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class InstructorCreate extends Component
{
    use WithFileUploads;

    public $firma;

    // Búsqueda
    public string $persona_dni = '';
    public string $persona_estado = 'idle'; // idle, found, sin_persona, multiples
    public ?int $persona_id = null;
    public ?array $personaSeleccionada = null;
    public array $resultadosBusqueda = [];

    // Crear persona inline
    public bool $showCrearPersona = false;
    public string $p_dni = '';
    public string $p_primer_nombre = '';
    public string $p_segundo_nombre = '';
    public string $p_primer_apellido = '';
    public string $p_segundo_apellido = '';
    public string $p_telefono = '';
    public string $p_email = '';
    public string $p_fecha_nacimiento = '';
    public string $p_sexo = '';

    // Datos instructor
    public ?int $feligres_id = null;
    public string $fecha_ingreso = '';
    public string $estado = 'Activo';

    public function mount(): void
    {
        $this->fecha_ingreso = now()->format('Y-m-d');
    }

    public function buscarPersona(): void
    {
        $this->resetErrorBag();
        $this->resultadosBusqueda = [];
        $this->personaSeleccionada = null;
        $this->persona_id = null;

        $valor = trim($this->persona_dni);

        if ($valor === '') {
            $this->addError('persona_dni', 'Ingresa un DNI o nombre para buscar.');
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
            $this->persona_estado = 'sin_persona';
            return;
        }

        if ($personas->count() === 1) {
            $this->seleccionarPersona($personas->first()->id);
            return;
        }

        $this->persona_estado = 'multiples';
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

    public function seleccionarPersona(int $id): void
    {
        $this->resetErrorBag();

        $persona = Persona::findOrFail($id);

        $this->persona_id = $persona->id;
        $this->personaSeleccionada = [
            'id' => $persona->id,
            'dni' => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono' => $persona->telefono,
            'email' => $persona->email,
        ];

        $this->persona_estado = 'found';
        $this->showCrearPersona = false;
        $this->resultadosBusqueda = [];
    }

    public function limpiarPersona(): void
    {
        $this->resetErrorBag();

        $this->persona_id = null;
        $this->personaSeleccionada = null;
        $this->persona_dni = '';
        $this->persona_estado = 'idle';
        $this->showCrearPersona = false;
        $this->resultadosBusqueda = [];

        $this->reset([
            'p_dni',
            'p_primer_nombre',
            'p_segundo_nombre',
            'p_primer_apellido',
            'p_segundo_apellido',
            'p_telefono',
            'p_email',
            'p_fecha_nacimiento',
            'p_sexo',
        ]);
    }

    public function abrirCrearPersona(): void
    {
        $this->resetErrorBag();

        $this->p_dni = preg_match('/^[0-9]+$/', trim($this->persona_dni)) ? trim($this->persona_dni) : '';

        $this->reset([
            'p_primer_nombre',
            'p_segundo_nombre',
            'p_primer_apellido',
            'p_segundo_apellido',
            'p_telefono',
            'p_email',
            'p_fecha_nacimiento',
            'p_sexo',
        ]);

        $this->showCrearPersona = true;
    }

    public function cancelarCrearPersona(): void
    {
        $this->showCrearPersona = false;
        $this->resetErrorBag();
    }

    public function crearPersona(): void
    {
        $this->resetErrorBag();

        $this->validate([
            'p_dni' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/^[0-9]+$/',
                Rule::unique('personas', 'dni'),
            ],
            'p_primer_nombre' => [
                'required',
                'string',
                'max:150',
                'regex:/^[\pL\s]+$/u',
            ],
            'p_segundo_nombre' => [
                'nullable',
                'string',
                'max:150',
                'regex:/^[\pL\s]+$/u',
            ],
            'p_primer_apellido' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\pL\s]+$/u',
            ],
            'p_segundo_apellido' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[\pL\s]+$/u',
            ],
            'p_telefono' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
            ],
            'p_email' => ['nullable', 'email', 'max:255'],
            'p_fecha_nacimiento' => ['required', 'date', 'before_or_equal:today'],
            'p_sexo' => ['required', 'in:M,F'],
        ], [
            'p_dni.required' => 'El DNI es obligatorio.',
            'p_dni.min' => 'El DNI debe tener al menos 8 caracteres.',
            'p_dni.max' => 'El DNI no puede exceder 20 caracteres.',
            'p_dni.regex' => 'El DNI solo debe contener números.',
            'p_dni.unique' => 'Ya existe una persona con ese DNI.',
            'p_sexo.required' => 'El sexo es obligatorio.',
            'p_sexo.in' => 'El sexo debe ser M o F.',
            'p_fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento debe ser hoy o una fecha anterior.',

            'p_primer_nombre.required' => 'El primer nombre es obligatorio.',
            'p_primer_nombre.max' => 'El primer nombre no puede exceder 150 caracteres.',
            'p_primer_nombre.regex' => 'El primer nombre solo debe contener letras y espacios.',

            'p_segundo_nombre.max' => 'El segundo nombre no puede exceder 150 caracteres.',
            'p_segundo_nombre.regex' => 'El segundo nombre solo debe contener letras y espacios.',

            'p_primer_apellido.required' => 'El primer apellido es obligatorio.',
            'p_primer_apellido.max' => 'El primer apellido no puede exceder 100 caracteres.',
            'p_primer_apellido.regex' => 'El primer apellido solo debe contener letras y espacios.',

            'p_segundo_apellido.max' => 'El segundo apellido no puede exceder 100 caracteres.',
            'p_segundo_apellido.regex' => 'El segundo apellido solo debe contener letras y espacios.',

            'p_telefono.required' => 'El teléfono es obligatorio.',
            'p_telefono.max' => 'El teléfono no puede exceder 20 caracteres.',
            'p_telefono.regex' => 'El teléfono solo debe contener números.',

            'p_email.email' => 'El correo electrónico no es válido.',
            'p_email.max' => 'El correo electrónico no puede exceder 255 caracteres.',

            'p_fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'p_fecha_nacimiento.date' => 'La fecha de nacimiento no es válida.',

            'p_sexo.in' => 'El sexo debe ser M o F.',
        ]);

        $persona = Persona::create([
            'dni' => trim($this->p_dni),
            'primer_nombre' => trim($this->p_primer_nombre),
            'segundo_nombre' => trim($this->p_segundo_nombre) ?: null,
            'primer_apellido' => trim($this->p_primer_apellido),
            'segundo_apellido' => trim($this->p_segundo_apellido) ?: null,
            'telefono' => trim($this->p_telefono) ?: null,
            'email' => trim($this->p_email) ?: null,
            'fecha_nacimiento' => $this->p_fecha_nacimiento ?: null,
            'sexo' => $this->p_sexo ?: null,
        ]);

        $this->seleccionarPersona($persona->id);

        session()->flash('persona_nueva', "Persona \"{$persona->nombre_completo}\" creada y seleccionada.");
    }

    public function guardar(): void
    {
        $this->resetErrorBag();

        $this->validate([
            'persona_id' => ['required', 'integer', 'exists:personas,id'],
            'firma' => ['nullable', 'image', 'max:2048'],
            'fecha_ingreso' => ['nullable', 'date'],
            'estado' => ['required', 'in:Activo,Inactivo'],
        ], [
            'persona_id.required' => 'Debes seleccionar una persona.',
            'persona_id.exists' => 'La persona seleccionada no existe.',
            'firma.image' => 'La firma debe ser una imagen válida.',
            'firma.max' => 'La firma no puede pesar más de 2 MB.',
            'fecha_ingreso.date' => 'La fecha de ingreso no es válida.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ]);

        $iglesiaId = TenantIglesia::currentId();

        if (! $iglesiaId) {
            $this->addError('persona_id', 'No se encontró la iglesia activa de la sesión.');
            return;
        }

        $feligres = Feligres::where('id_persona', $this->persona_id)
            ->where('id_iglesia', $iglesiaId)
            ->first();

        if (! $feligres) {
            $feligres = Feligres::create([
                'id_persona' => $this->persona_id,
                'id_iglesia' => $iglesiaId,
                'fecha_ingreso' => $this->fecha_ingreso ?: now()->format('Y-m-d'),
                'estado' => 'Activo',
                'created_by' => Auth::id(),
            ]);
        }

        $this->feligres_id = $feligres->id;

        $instructorExistente = Instructor::withTrashed()
            ->where('feligres_id', $feligres->id)
            ->first();

        if ($instructorExistente) {
            if ($instructorExistente->trashed()) {
                $pathFirma = $this->firma
                    ? $this->firma->store('firmas', 'public')
                    : $instructorExistente->path_firma;

                $instructorExistente->restore();
                $instructorExistente->update([
                    'path_firma' => $pathFirma,
                    'fecha_ingreso' => $this->fecha_ingreso ?: $instructorExistente->fecha_ingreso,
                    'estado' => $this->estado,
                    'updated_by' => Auth::id(),
                ]);

                session()->flash('success', 'Instructor restaurado correctamente.');
                $this->redirect(route('instructor.index'), navigate: false);
                return;
            }

            $this->addError('persona_id', 'La persona seleccionada ya está registrada como instructor.');
            return;
        }

        $pathFirma = $this->firma ? $this->firma->store('firmas', 'public') : null;

        Instructor::create([
            'feligres_id' => $feligres->id,
            'path_firma' => $pathFirma,
            'fecha_ingreso' => $this->fecha_ingreso ?: null,
            'estado' => $this->estado,
            'created_by' => Auth::id(),
        ]);

        session()->flash('success', 'Instructor registrado correctamente.');
        $this->redirect(route('instructor.index'), navigate: false);
    }

    public function render()
    {
        return view('livewire.instructor.instructor-create');
    }
}