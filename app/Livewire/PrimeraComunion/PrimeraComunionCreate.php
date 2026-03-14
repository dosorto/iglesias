<?php

namespace App\Livewire\PrimeraComunion;

use Livewire\Component;
use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Iglesias;
use App\Models\TenantIglesia;
use App\Models\PrimeraComunion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PrimeraComunionCreate extends Component
{
    // Wizard
    public int $paso = 1;

    // Iglesia (auto-set desde tenant)
    public $iglesia_id = null;

    // Fecha (se captura en paso 2)
    public string $fecha_primera_comunion = '';

    // Roles - paso 1
    public string $feligres_dni         = '';
    public ?array $feligres_persona     = null;
    public ?int   $feligres_feligres_id = null;
    public string $feligres_estado      = 'idle';

    public string $catequista_dni         = '';
    public ?array $catequista_persona     = null;
    public ?int   $catequista_feligres_id = null;
    public string $catequista_estado      = 'idle';

    public string $ministro_dni         = '';
    public ?array $ministro_persona     = null;
    public ?int   $ministro_feligres_id = null;
    public string $ministro_estado      = 'idle';

    public string $parroco_dni         = '';
    public ?array $parroco_persona     = null;
    public ?int   $parroco_feligres_id = null;
    public string $parroco_estado      = 'idle';

    // Búsqueda con resultados múltiples (compartido)
    public array   $busqueda_resultados = [];
    public ?string $busqueda_rol        = null;

    // Mini-form compartido
    public ?string $mini_rol  = null;
    public ?string $mini_tipo = null;

    public string $mini_p_dni              = '';
    public string $mini_p_primer_nombre    = '';
    public string $mini_p_segundo_nombre   = '';
    public string $mini_p_primer_apellido  = '';
    public string $mini_p_segundo_apellido = '';
    public string $mini_p_fecha_nacimiento = '';
    public string $mini_p_sexo             = '';
    public string $mini_p_telefono         = '';
    public string $mini_p_email            = '';

    public string $mini_f_fecha_ingreso = '';
    public string $mini_f_estado        = 'Activo';

    // Paso 2 - Libro parroquial
    public string $libro_comunion = '';
    public string $folio          = '';
    public string $partida_numero = '';
    public string $observaciones  = '';

    public function mount(): void
    {
        $this->fecha_primera_comunion = now()->format('Y-m-d');
        $this->mini_f_fecha_ingreso   = now()->format('Y-m-d');

        if (session('tenant')) {
            $this->iglesia_id = TenantIglesia::currentId();
        }
    }

    // ─── Navegación ────────────────────────────────────────────────────────────

    public function siguientePaso(): void
    {
        if ($this->paso === 1) {
            if (! $this->feligres_feligres_id) {
                $this->addError('feligres_dni', 'El comulgante es obligatorio y debe estar registrado como feligrés.');
                return;
            }
        }

        $this->paso++;
        $this->resetErrorBag();
    }

    public function anteriorPaso(): void
    {
        if ($this->paso > 1) {
            $this->paso--;
            $this->resetErrorBag();
        }
    }

    // ─── Búsqueda por DNI o nombre ─────────────────────────────────────────────

    public function buscarPersona(string $rol): void
    {
        $input = trim($this->{"{$rol}_dni"});

        if (empty($input)) {
            $this->addError("{$rol}_dni", 'Ingresa un DNI o nombre para buscar.');
            return;
        }

        if (ctype_digit($input)) {
            // Búsqueda exacta por DNI
            $personas = Persona::where('dni', $input)->get();
        } else {
            if (mb_strlen($input) < 3) {
                $this->addError("{$rol}_dni", 'Ingresa al menos 3 caracteres para buscar por nombre.');
                return;
            }
            $term = '%' . $input . '%';
            $personas = Persona::where(function ($q) use ($term) {
                $q->where('primer_nombre',    'like', $term)
                  ->orWhere('segundo_nombre',   'like', $term)
                  ->orWhere('primer_apellido',  'like', $term)
                  ->orWhere('segundo_apellido', 'like', $term);
            })->orderBy('primer_apellido')->orderBy('primer_nombre')->limit(15)->get();
        }

        if ($personas->isEmpty()) {
            $this->{"{$rol}_persona"}     = null;
            $this->{"{$rol}_feligres_id"} = null;
            $this->{"{$rol}_estado"}      = 'sin_persona';
            $this->busqueda_resultados    = [];
            $this->busqueda_rol           = null;
            return;
        }

        if ($personas->count() === 1) {
            $this->asignarPersonaARol($rol, $personas->first());
            return;
        }

        // Múltiples resultados — mostrar lista
        $this->busqueda_resultados = $personas->map(fn ($p) => [
            'id'              => $p->id,
            'dni'             => $p->dni,
            'nombre_completo' => $p->nombre_completo,
            'telefono'        => $p->telefono ?? null,
        ])->toArray();

        $this->busqueda_rol      = $rol;
        $this->{"{$rol}_estado"} = 'multiples';
    }

    // ─── Seleccionar resultado de la lista ─────────────────────────────────────

    public function seleccionarResultado(int $personaId): void
    {
        $rol = $this->busqueda_rol;
        if (! $rol) return;

        $persona = Persona::findOrFail($personaId);
        $this->asignarPersonaARol($rol, $persona);
    }

    // ─── Asignar persona a rol con validación de duplicados ────────────────────

    private function asignarPersonaARol(string $rol, Persona $persona): void
    {
        $roles = ['feligres', 'catequista', 'ministro', 'parroco'];
        $labels = [
            'feligres'   => 'Comulgante',
            'catequista' => 'Catequista',
            'ministro'   => 'Ministro',
            'parroco'    => 'Párroco',
        ];

        // Validar que la persona no esté ya en otro rol
        foreach ($roles as $r) {
            if ($r === $rol) continue;
            $existente = $this->{"{$r}_persona"};
            if ($existente && $existente['id'] === $persona->id) {
                $this->addError("{$rol}_dni", "Esta persona ya está asignada como {$labels[$r]}.");
                return;
            }
        }

        $feligres = Feligres::where('id_persona', $persona->id)->first();

        $this->{"{$rol}_persona"} = [
            'id'              => $persona->id,
            'dni'             => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono'        => $persona->telefono ?? null,
            'email'           => $persona->email    ?? null,
        ];
        $this->{"{$rol}_dni"} = $persona->dni;

        if ($feligres) {
            $this->{"{$rol}_feligres_id"} = $feligres->id;
            $this->{"{$rol}_estado"}      = 'found';
        } else {
            $this->{"{$rol}_feligres_id"} = null;
            $this->{"{$rol}_estado"}      = 'sin_feligres';
        }

        $this->busqueda_resultados = [];
        $this->busqueda_rol        = null;

        if ($this->mini_rol === $rol) {
            $this->cancelarMini();
        }
    }

    // ─── Limpiar un rol ────────────────────────────────────────────────────────

    public function limpiarRol(string $rol): void
    {
        $this->{"{$rol}_dni"}         = '';
        $this->{"{$rol}_persona"}     = null;
        $this->{"{$rol}_feligres_id"} = null;
        $this->{"{$rol}_estado"}      = 'idle';

        if ($this->busqueda_rol === $rol) {
            $this->busqueda_resultados = [];
            $this->busqueda_rol        = null;
        }

        if ($this->mini_rol === $rol) {
            $this->cancelarMini();
        }
    }

    // ─── Mini-form: abrir Crear Persona ────────────────────────────────────────

    public function abrirCrearPersona(string $rol): void
    {
        $input = trim($this->{"{$rol}_dni"});

        $this->mini_rol   = $rol;
        $this->mini_tipo  = 'persona';
        $this->mini_p_dni = ctype_digit($input) ? $input : '';

        $this->reset([
            'mini_p_primer_nombre', 'mini_p_segundo_nombre',
            'mini_p_primer_apellido', 'mini_p_segundo_apellido',
            'mini_p_fecha_nacimiento', 'mini_p_sexo',
            'mini_p_telefono', 'mini_p_email',
        ]);

        $this->resetErrorBag();
    }

    // ─── Mini-form: abrir Registrar como Feligrés ──────────────────────────────

    public function abrirRegistrarFeligres(string $rol): void
    {
        $this->mini_rol             = $rol;
        $this->mini_tipo            = 'feligres';
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
        $this->mini_f_estado        = 'Activo';

        $this->resetErrorBag();
    }

    // ─── Mini-form: cancelar ───────────────────────────────────────────────────

    public function cancelarMini(): void
    {
        $this->mini_rol  = null;
        $this->mini_tipo = null;

        $this->reset([
            'mini_p_dni', 'mini_p_primer_nombre', 'mini_p_segundo_nombre',
            'mini_p_primer_apellido', 'mini_p_segundo_apellido',
            'mini_p_fecha_nacimiento', 'mini_p_sexo',
            'mini_p_telefono', 'mini_p_email',
        ]);

        $this->mini_f_estado        = 'Activo';
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
        $this->resetErrorBag();
    }

    // ─── Mini-form: guardar nueva persona + feligrés ───────────────────────────

    public function guardarMiniPersona(): void
    {
        if (session('tenant')) {
            $this->iglesia_id = TenantIglesia::currentId();
        }

        $this->validate([
            'mini_p_dni'              => ['required', 'string', 'min:8', 'max:20', Rule::unique('personas', 'dni')],
            'mini_p_primer_nombre'    => ['required', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_primer_apellido'  => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_segundo_nombre'   => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_segundo_apellido' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_fecha_nacimiento' => ['required', 'date', 'before:today'],
            'mini_p_sexo'             => ['required', 'in:M,F'],
            'mini_p_telefono'         => ['required', 'string', 'max:20', 'regex:/^[0-9+\-]+$/'],
            'mini_p_email'            => ['nullable', 'email', 'max:255'],
            'mini_f_fecha_ingreso'    => ['nullable', 'date'],
            'mini_f_estado'           => ['required', 'in:Activo,Inactivo'],
        ], [
            'mini_p_dni.required'              => 'El número de identidad es obligatorio.',
            'mini_p_dni.min'                   => 'El DNI debe tener al menos 8 caracteres.',
            'mini_p_dni.unique'                => 'Ya existe una persona con ese DNI.',
            'mini_p_primer_nombre.required'    => 'El primer nombre es obligatorio.',
            'mini_p_primer_nombre.regex'       => 'El primer nombre solo puede contener letras.',
            'mini_p_primer_apellido.required'  => 'El primer apellido es obligatorio.',
            'mini_p_primer_apellido.regex'     => 'El primer apellido solo puede contener letras.',
            'mini_p_fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'mini_p_fecha_nacimiento.before'   => 'La fecha de nacimiento debe ser anterior a hoy.',
            'mini_p_sexo.required'             => 'El sexo es obligatorio.',
            'mini_p_sexo.in'                   => 'Selecciona Masculino o Femenino.',
            'mini_p_telefono.required'         => 'El teléfono es obligatorio.',
            'mini_p_telefono.regex'            => 'El teléfono solo puede contener números, + y -.',
        ]);

        $rol = $this->mini_rol;

        DB::transaction(function () use ($rol) {
            $persona = Persona::create([
                'dni'              => $this->mini_p_dni,
                'primer_nombre'    => $this->mini_p_primer_nombre,
                'segundo_nombre'   => $this->mini_p_segundo_nombre  ?: null,
                'primer_apellido'  => $this->mini_p_primer_apellido,
                'segundo_apellido' => $this->mini_p_segundo_apellido ?: null,
                'fecha_nacimiento' => $this->mini_p_fecha_nacimiento ?: null,
                'sexo'             => $this->mini_p_sexo,
                'telefono'         => $this->mini_p_telefono ?: null,
                'email'            => $this->mini_p_email    ?: null,
            ]);

            $feligres = Feligres::create([
                'id_persona'    => $persona->id,
                'id_iglesia'    => $this->iglesia_id,
                'fecha_ingreso' => $this->mini_f_fecha_ingreso ?: now()->format('Y-m-d'),
                'estado'        => $this->mini_f_estado,
            ]);

            $this->{"{$rol}_persona"} = [
                'id'              => $persona->id,
                'dni'             => $persona->dni,
                'nombre_completo' => $persona->nombre_completo,
                'telefono'        => $persona->telefono ?? null,
                'email'           => $persona->email    ?? null,
            ];

            $this->{"{$rol}_feligres_id"} = $feligres->id;
            $this->{"{$rol}_estado"}      = 'found';
            $this->{"{$rol}_dni"}         = $persona->dni;
        });

        $this->cancelarMini();
    }

    // ─── Mini-form: registrar persona existente como feligrés ──────────────────

    public function guardarMiniFeligres(): void
    {
        if (session('tenant')) {
            $this->iglesia_id = TenantIglesia::currentId();
        }

        $this->validate([
            'iglesia_id'           => ['required'],
            'mini_f_fecha_ingreso' => ['nullable', 'date'],
            'mini_f_estado'        => ['required', 'in:Activo,Inactivo'],
        ], [
            'iglesia_id.required' => 'No se pudo determinar la iglesia.',
        ]);

        $rol     = $this->mini_rol;
        $persona = $this->{"{$rol}_persona"};

        $feligres = Feligres::create([
            'id_persona'    => $persona['id'],
            'id_iglesia'    => $this->iglesia_id,
            'fecha_ingreso' => $this->mini_f_fecha_ingreso ?: null,
            'estado'        => $this->mini_f_estado,
        ]);

        $this->{"{$rol}_feligres_id"} = $feligres->id;
        $this->{"{$rol}_estado"}      = 'found';

        $this->cancelarMini();
    }

    // ─── Guardar primera comunión final ────────────────────────────────────────

    public function guardar(): void
    {
        if (session('tenant')) {
            $this->iglesia_id = TenantIglesia::currentId();
        }

        $this->validate([
            'fecha_primera_comunion' => ['required', 'date'],
        ], [
            'fecha_primera_comunion.required' => 'La fecha de primera comunión es obligatoria.',
            'fecha_primera_comunion.date'     => 'La fecha de primera comunión no es válida.',
        ]);

        if (! $this->feligres_feligres_id) {
            $this->addError('feligres_dni', 'El comulgante es obligatorio.');
            return;
        }

        PrimeraComunion::create([
            'id_iglesia'             => $this->iglesia_id,
            'fecha_primera_comunion' => $this->fecha_primera_comunion,
            'id_feligres'            => $this->feligres_feligres_id,
            'id_catequista'          => $this->catequista_feligres_id,
            'id_ministro'            => $this->ministro_feligres_id,
            'id_parroco'             => $this->parroco_feligres_id,
            'libro_comunion'         => $this->libro_comunion ?: null,
            'folio'                  => $this->folio          ?: null,
            'partida_numero'         => $this->partida_numero ?: null,
            'observaciones'          => $this->observaciones  ?: null,
        ]);

        session()->flash('success', 'Primera comunión registrada correctamente.');
        $this->redirect(route('primera-comunion.index'), navigate: false);
    }

    public function render()
    {
        $centralConn = config('tenancy.central_connection', 'mysql');

        if (session('tenant')) {
            $iglesias = collect([TenantIglesia::current()])->filter();
        } else {
            $iglesias = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();
        }

        return view('livewire.primera-comunion.primera-comunion-create', [
            'iglesias' => $iglesias,
        ]);
    }
}