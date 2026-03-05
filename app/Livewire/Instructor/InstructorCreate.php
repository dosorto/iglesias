<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Persona;
use App\Models\Instructor;
use App\Models\Feligres;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class InstructorCreate extends Component
{

    use WithFileUploads;

    // ── Nueva propiedad para la firma ─────────────────────────────
    public $firma; // para subir la imagen
    // ── Búsqueda live ──────────────────────────────────────────────
    public string $search = '';
    public ?int   $persona_id = null;
    public ?array $personaSeleccionada = null;

    // ── Crear persona inline ────────────────────────────────────────
    public bool   $showCrearPersona  = false;
    public string $p_dni             = '';
    public string $p_primer_nombre   = '';
    public string $p_segundo_nombre  = '';
    public string $p_primer_apellido  = '';
    public string $p_segundo_apellido = '';
    public string $p_telefono         = '';
    public string $p_email            = '';
    public string $p_fecha_nacimiento = '';
    public string $p_sexo             = '';

    // ── Datos del instructor / feligrés ────────────────────────────
    public ?int   $feligres_id = null;
    public string $fecha_ingreso = '';
    public string $estado        = 'Activo';


    public function mount(): void
    {
        $this->fecha_ingreso = now()->format('Y-m-d');
    }

    // ── Resultados de búsqueda en vivo ─────────────────────────────
    #[Computed]
    public function resultados(): \Illuminate\Support\Collection
    {
        $q = trim($this->search);

        if (strlen($q) < 2) {
            return collect();
        }

        return Persona::where(function ($query) use ($q) {
                $query->where('dni', 'like', "%{$q}%")
                      ->orWhere('primer_nombre',    'like', "%{$q}%")
                      ->orWhere('segundo_nombre',   'like', "%{$q}%")
                      ->orWhere('primer_apellido',  'like', "%{$q}%")
                      ->orWhere('segundo_apellido', 'like', "%{$q}%");
            })
            ->orderBy('primer_apellido')
            ->orderBy('primer_nombre')
            ->limit(10)
            ->get();
    }

    // Auto-abrir form crear cuando no hay resultados
    public function updatedSearch(): void
    {
        unset($this->resultados);

        $q = trim($this->search);

        if (strlen($q) < 2) {
            $this->showCrearPersona = false;
            return;
        }

        if ($this->resultados->isEmpty()) {
            if (! $this->showCrearPersona) {
                $this->showCrearPersona = true;
                $this->p_dni = ctype_digit($q) ? $q : '';
                $this->reset(['p_primer_nombre', 'p_segundo_nombre', 'p_primer_apellido',
                              'p_segundo_apellido', 'p_telefono', 'p_email',
                              'p_fecha_nacimiento', 'p_sexo']);
            }
        } else {
            $this->showCrearPersona = false;
        }
    }

    // ── Seleccionar persona del listado ─────────────────────────────
    public function seleccionarPersona(int $id): void
    {
        $persona = Persona::findOrFail($id);

        $this->persona_id = $persona->id;
        $this->personaSeleccionada = [
            'id'              => $persona->id,
            'dni'             => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono'        => $persona->telefono,
            'email'           => $persona->email,
        ];

        $this->search           = '';
        $this->showCrearPersona = false;

        unset($this->resultados);
    }

    // ── Deseleccionar persona ───────────────────────────────────────
    public function limpiarPersona(): void
    {
        $this->persona_id          = null;
        $this->personaSeleccionada = null;
        $this->search              = '';
        $this->showCrearPersona    = false;
        unset($this->resultados);
    }

    // ── Mostrar / ocultar form crear persona ────────────────────────
    public function toggleCrearPersona(): void
    {
        if ($this->showCrearPersona) {
            $this->showCrearPersona = false;
            $this->search = '';
            unset($this->resultados);
        } else {
            $q = trim($this->search);
            $this->p_dni = ctype_digit($q) ? $q : '';
            $this->reset(['p_primer_nombre', 'p_segundo_nombre', 'p_primer_apellido',
                          'p_segundo_apellido', 'p_telefono', 'p_email',
                          'p_fecha_nacimiento', 'p_sexo']);
            $this->search = '';
            unset($this->resultados);
            $this->showCrearPersona = true;
        }
    }
        // ── Crear persona inline ─────────────────────────────────────────
    public function crearPersona(): void
    {
        $this->validate([
            'p_dni'             => ['required', 'string', 'min:8', 'max:20', Rule::unique('personas', 'dni')],
            'p_primer_nombre'   => ['required', 'string', 'max:150'],
            'p_primer_apellido' => ['required', 'string', 'max:100'],
            'p_segundo_nombre'  => ['nullable', 'string', 'max:150'],
            'p_segundo_apellido'=> ['nullable', 'string', 'max:100'],
            'p_telefono'          => ['nullable', 'string', 'max:20'],
            'p_email'             => ['nullable', 'email', 'max:255'],
            'p_fecha_nacimiento'  => ['nullable', 'date'],
            'p_sexo'              => ['nullable', 'in:M,F'],
        ], [
            'p_dni.required'             => 'El número de identidad es obligatorio.',
            'p_dni.min'                  => 'El DNI debe tener al menos 8 caracteres.',
            'p_dni.unique'               => 'Ya existe una persona con ese DNI.',
            'p_primer_nombre.required'   => 'El primer nombre es obligatorio.',
            'p_primer_apellido.required' => 'El primer apellido es obligatorio.',
        ]);

        $persona = Persona::create([
            'dni'              => $this->p_dni,
            'primer_nombre'    => $this->p_primer_nombre,
            'segundo_nombre'   => $this->p_segundo_nombre ?: null,
            'primer_apellido'  => $this->p_primer_apellido,
            'segundo_apellido' => $this->p_segundo_apellido ?: null,
            'telefono'          => $this->p_telefono ?: null,
            'email'             => $this->p_email ?: null,
            'fecha_nacimiento'  => $this->p_fecha_nacimiento ?: null,
            'sexo'              => $this->p_sexo ?: null,
        ]);

        $this->seleccionarPersona($persona->id);
        session()->flash('persona_nueva', "Persona \"{$persona->nombre_completo}\" creada y seleccionada.");
    }

    // ── Guardar instructor ───────────────────────────────────────────
    public function guardar(): void
    {
        // 1️⃣ Validar datos
        $this->validate([
            'persona_id' => ['required', 'integer', 'exists:personas,id'],
            'firma'      => ['required', 'image', 'max:2048'], // validar imagen <=2MB
            'fecha_ingreso' => ['nullable', 'date'],
            'estado'        => ['required', 'in:Activo,Inactivo'],
        ]);

        // 2️⃣ Crear o recuperar feligrés automáticamente
        $feligres = Feligres::firstOrCreate(
            ['id_persona' => $this->persona_id],
            ['id_iglesia' => \App\Models\Iglesias::first()->id]
        );

        // 3️⃣ Revisar si ya tiene instructor
        if (Instructor::where('feligres_id', $feligres->id)->exists()) {
            $this->addError('persona_id', 'Esta persona ya tiene un instructor asignado.');
            return;
        }

        // 4️⃣ Guardar la firma en storage
        $pathFirma = $this->firma->store('firmas', 'public');

        // 5️⃣ Crear instructor
        Instructor::create([
            'feligres_id'   => $feligres->id,
            'path_firma'    => $pathFirma,
            'fecha_ingreso' => $this->fecha_ingreso ?: null,
            'estado'        => $this->estado,
        ]);

        // 6️⃣ Mensaje y redirección
        session()->flash('success', 'Instructor registrado correctamente.');
        $this->redirect(route('instructor.index'), navigate: false);
    }

    public function render()
    {
        return view('livewire.instructor.instructor-create');
    }
}
