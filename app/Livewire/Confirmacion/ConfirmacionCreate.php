<?php

namespace App\Livewire\Confirmacion;

use Livewire\Component;
use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Iglesias;
use App\Models\TenantIglesia;
use App\Models\Confirmacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ConfirmacionCreate extends Component
{
    private const LUGAR_CONFIRMACION_FIJO = 'Monjaras, Marcovia';

    // Wizard
    public int $paso = 1;

    // Paso 1
    public $iglesia_id           = null;
    public string $fecha_confirmacion = '';
    public string $lugar_confirmacion = '';
    public $ministro_feligres_id = null;

    // Confirmado
    public string $confirmado_dni         = '';
    public ?array $confirmado_persona     = null;
    public ?int   $confirmado_feligres_id = null;
    public string $confirmado_estado      = 'idle';

    // Búsqueda con resultados múltiples
    public array   $busqueda_resultados = [];
    public ?string $busqueda_rol        = null;

    public string $padre_dni         = '';
    public ?array $padre_persona     = null;
    public ?int   $padre_feligres_id = null;
    public string $padre_estado      = 'idle';

    public string $madre_dni         = '';
    public ?array $madre_persona     = null;
    public ?int   $madre_feligres_id = null;
    public string $madre_estado      = 'idle';

    public string $padrino_dni         = '';
    public ?array $padrino_persona     = null;
    public ?int   $padrino_feligres_id = null;
    public string $padrino_estado      = 'idle';

    public string $madrina_dni         = '';
    public ?array $madrina_persona     = null;
    public ?int   $madrina_feligres_id = null;
    public string $madrina_estado      = 'idle';

    public string $ministro_dni         = '';
    public ?array $ministro_persona     = null;
    public string $ministro_estado      = 'idle';

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

    public string $advertenciaDuplicado = '';
    public bool   $confirmarDuplicado   = false;

    // Paso 2
    public string $libro_confirmacion = '';
    public string $folio              = '';
    public string $partida_numero     = '';
    public string $observaciones      = '';
    public string $nota_marginal      = '';

    public function mount(): void
    {
        $this->fecha_confirmacion   = now()->format('Y-m-d');
        $this->lugar_confirmacion   = self::LUGAR_CONFIRMACION_FIJO;
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
        $this->iglesia_id           = TenantIglesia::currentId();
    }

    // Navegación

    public function siguientePaso(): void
    {
        if ($this->paso === 1) {
            if (! $this->confirmado_feligres_id) {
                $this->addError('confirmado_dni', 'El confirmado es obligatorio y debe estar registrado como feligrés.');
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

    // Buscar persona por DNI o nombre

    public function buscarPersona(string $rol): void
    {
        $input = trim($this->{"{$rol}_dni"});

        if (empty($input)) {
            $this->addError("{$rol}_dni", 'Ingresa un DNI o nombre para buscar.');
            return;
        }

        if (ctype_digit($input)) {
            $personas = Persona::where('dni', $input)->get();
        } else {
            if (mb_strlen($input) < 3) {
                $this->addError("{$rol}_dni", 'Ingresa al menos 3 caracteres para buscar por nombre.');
                return;
            }
            $term     = '%' . $input . '%';
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

        // Múltiples resultados — para el confirmado, excluir ya confirmados
        if ($rol === 'confirmado') {
            $confirmadoFeligresIds = Confirmacion::pluck('feligres_id')->toArray();
            $feligresPersonaIds    = Feligres::whereIn('id', $confirmadoFeligresIds)->pluck('id_persona')->toArray();
            $personas              = $personas->whereNotIn('id', $feligresPersonaIds);

            if ($personas->isEmpty()) {
                $this->{"{$rol}_estado"} = 'sin_persona';
                $this->addError("{$rol}_dni", 'No se encontraron personas disponibles (todas ya fueron confirmadas).');
                return;
            }
        }

        $this->busqueda_resultados = $personas->map(fn ($p) => [
            'id'              => $p->id,
            'dni'             => $p->dni,
            'nombre_completo' => $p->nombre_completo,
            'telefono'        => $p->telefono ?? null,
        ])->toArray();

        $this->busqueda_rol          = $rol;
        $this->{"{$rol}_estado"}     = 'multiples';
    }

    public function seleccionarResultado(int $personaId): void
    {
        $rol = $this->busqueda_rol;
        if (! $rol) return;

        $persona = Persona::findOrFail($personaId);
        $this->asignarPersonaARol($rol, $persona);
    }

    private function asignarPersonaARol(string $rol, Persona $persona): void
    {
        $roles  = ['confirmado', 'padre', 'madre', 'padrino', 'madrina', 'ministro'];
        $labels = [
            'confirmado' => 'Confirmado',
            'padre'      => 'Padre',
            'madre'      => 'Madre',
            'padrino'    => 'Padrino',
            'madrina'    => 'Madrina',
            'ministro'   => 'Ministro',
        ];

        foreach ($roles as $r) {
            if ($r === $rol) continue;
            $existente = $this->{"{$r}_persona"};
            if ($existente && $existente['id'] === $persona->id) {
                $this->addError("{$rol}_dni", "Esta persona ya está asignada como {$labels[$r]}.");
                return;
            }
        }

        $feligres = Feligres::where('id_persona', $persona->id)->first();

        // Validar que el confirmado no haya sido confirmado anteriormente
        if ($rol === 'confirmado' && $feligres) {
            $yaConfirmado = Confirmacion::where('feligres_id', $feligres->id)->exists();
            if ($yaConfirmado) {
                $this->addError("{$rol}_dni", 'Esta persona ya fue confirmada anteriormente.');
                return;
            }
        }

        $this->{"{$rol}_persona"} = [
            'id'              => $persona->id,
            'dni'             => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono'        => $persona->telefono ?? null,
            'email'           => $persona->email    ?? null,
        ];
        $this->{"{$rol}_dni"} = $persona->dni;

        // El ministro no necesita ser feligrés obligatoriamente
        if ($rol === 'ministro') {
            $this->ministro_feligres_id = $feligres?->id;
            $this->ministro_estado      = 'found';
        } elseif ($feligres) {
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

    public function limpiarRol(string $rol): void
    {
        $this->{"{$rol}_dni"}     = '';
        $this->{"{$rol}_persona"} = null;
        $this->{"{$rol}_estado"}  = 'idle';

        if ($rol === 'ministro') {
            $this->ministro_feligres_id = null;
        } else {
            $this->{"{$rol}_feligres_id"} = null;
        }

        if ($this->busqueda_rol === $rol) {
            $this->busqueda_resultados = [];
            $this->busqueda_rol        = null;
        }

        if ($this->mini_rol === $rol) {
            $this->cancelarMini();
        }
    }

    public function abrirCrearPersona(string $rol): void
    {
        $dni = trim($this->{"{$rol}_dni"});

        $this->mini_rol   = $rol;
        $this->mini_tipo  = 'persona';
        $this->mini_p_dni = ctype_digit($dni) ? $dni : '';

        $this->reset([
            'mini_p_primer_nombre', 'mini_p_segundo_nombre',
            'mini_p_primer_apellido', 'mini_p_segundo_apellido',
            'mini_p_telefono', 'mini_p_email',
        ]);

        $this->resetErrorBag();
    }

    public function abrirRegistrarFeligres(string $rol): void
    {
        $this->mini_rol             = $rol;
        $this->mini_tipo            = 'feligres';
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
        $this->mini_f_estado        = 'Activo';

        $this->resetErrorBag();
    }

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

    public function confirmarYGuardarMiniPersona(): void
    {
        $this->confirmarDuplicado = true;
        $this->guardarMiniPersona();
    }

    public function guardarMiniPersona(): void
    {
        $this->validate([
            'mini_p_dni'              => ['nullable', 'string', 'min:8', 'max:20', Rule::unique('personas', 'dni')],
            'mini_p_primer_nombre'    => ['required', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_primer_apellido'  => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_segundo_nombre'   => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_segundo_apellido' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_fecha_nacimiento' => ['required', 'date', 'before:today'],
            'mini_p_sexo'             => ['required', 'in:M,F'],
            'mini_p_telefono'         => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-]+$/'],
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
            'mini_p_telefono.required'         => 'El teléfono es obligatorio.',
            'mini_p_telefono.regex'            => 'El teléfono solo puede contener números, + y -.',
        ]);

        $rol = $this->mini_rol;

        if (! $this->mini_p_dni && ! $this->confirmarDuplicado) {
            $duplicado = Persona::where('primer_nombre', $this->mini_p_primer_nombre)
                ->where('primer_apellido', $this->mini_p_primer_apellido)
                ->whereNull('dni')
                ->first();
            if ($duplicado) {
                $this->advertenciaDuplicado = $duplicado->nombre_completo;
                return;
            }
        }
        $this->advertenciaDuplicado = '';
        $this->confirmarDuplicado   = false;

        DB::transaction(function () use ($rol) {
            if (session('tenant')) {
                $this->iglesia_id = TenantIglesia::currentId();
            }

            $persona = Persona::create([
                'dni'              => $this->mini_p_dni ?: null,
                'primer_nombre'    => $this->mini_p_primer_nombre,
                'segundo_nombre'   => $this->mini_p_segundo_nombre  ?: null,
                'primer_apellido'  => $this->mini_p_primer_apellido,
                'segundo_apellido' => $this->mini_p_segundo_apellido ?: null,
                'fecha_nacimiento' => $this->mini_p_fecha_nacimiento ?: null,
                'sexo'             => $this->mini_p_sexo === 'Masculino' ? 'M' : ($this->mini_p_sexo === 'Femenino' ? 'F' : null),
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

            if ($rol === 'ministro') {
                $this->ministro_feligres_id = $feligres->id;
            } else {
                $this->{"{$rol}_feligres_id"} = $feligres->id;
            }

            $this->{"{$rol}_estado"} = 'found';
            $this->{"{$rol}_dni"}    = $persona->dni;
        });

        $this->cancelarMini();
    }

    public function guardarMiniFeligres(): void
    {
        $this->validate([
            'iglesia_id'           => ['required'],
            'mini_f_fecha_ingreso' => ['nullable', 'date'],
            'mini_f_estado'        => ['required', 'in:Activo,Inactivo'],
        ], [
            'iglesia_id.required' => 'No se pudo determinar la iglesia.',
        ]);

        if (session('tenant')) {
            $this->iglesia_id = TenantIglesia::currentId();
        }

        $this->lugar_confirmacion = self::LUGAR_CONFIRMACION_FIJO;

        $rol     = $this->mini_rol;
        $persona = $this->{"{$rol}_persona"};

        $feligres = Feligres::create([
            'id_persona'    => $persona['id'],
            'id_iglesia'    => $this->iglesia_id,
            'fecha_ingreso' => $this->mini_f_fecha_ingreso ?: null,
            'estado'        => $this->mini_f_estado,
        ]);

        if ($rol === 'ministro') {
            $this->ministro_feligres_id = $feligres->id;
        } else {
            $this->{"{$rol}_feligres_id"} = $feligres->id;
        }

        $this->{"{$rol}_estado"} = 'found';
        $this->cancelarMini();
    }

    public function guardar(): void
    {
        if (session('tenant')) {
            $this->iglesia_id = TenantIglesia::currentId();
        }

        $this->fecha_confirmacion = $this->fecha_confirmacion ?: now()->format('Y-m-d');

        $this->validate([
            'fecha_confirmacion' => ['required', 'date'],
        ], [
            'fecha_confirmacion.required' => 'La fecha de confirmación es obligatoria.',
            'fecha_confirmacion.date'     => 'La fecha de confirmación no es válida.',
        ]);

        if (! $this->confirmado_feligres_id) {
            $this->addError('confirmado_dni', 'El confirmado es obligatorio.');
            return;
        }

        if (!$this->validarGeneroRoles()) {
            return;
        }

        if (! $this->validarFechaPosteriorNacimiento(
            $this->confirmado_feligres_id,
            $this->fecha_confirmacion,
            'fecha_confirmacion',
            'confirmación'
        )) {
            return;
        }

        Confirmacion::create([
            'iglesia_id'          => $this->iglesia_id,
            'fecha_confirmacion'  => $this->fecha_confirmacion,
            'lugar_confirmacion'  => $this->lugar_confirmacion ?: null,
            'feligres_id'         => $this->confirmado_feligres_id,
            'padre_id'            => $this->padre_feligres_id,
            'madre_id'            => $this->madre_feligres_id,
            'padrino_id'          => $this->padrino_feligres_id,
            'madrina_id'          => $this->madrina_feligres_id,
            'ministro_id'         => $this->ministro_feligres_id,
            'libro_confirmacion'  => $this->libro_confirmacion ?: null,
            'folio'               => $this->folio              ?: null,
            'partida_numero'      => $this->partida_numero     ?: null,
            'observaciones'       => $this->observaciones      ?: null,
            'nota_marginal'       => $this->nota_marginal      ?: null,
        ]);

        session()->flash('success', 'Confirmación registrada correctamente.');
        $this->redirect(route('confirmacion.index'), navigate: false);
    }

    public function render()
    {
        $centralConn = config('tenancy.central_connection', 'mysql');

        if (session('tenant')) {
            $iglesias = collect([TenantIglesia::current()])->filter();
        } else {
            $iglesias = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();
        }

        return view('livewire.confirmacion.confirmacion-create', [
            'iglesias' => $iglesias,
        ]);
    }

    private function validarFechaPosteriorNacimiento(?int $feligresId, string $fechaSacramento, string $campo, string $label): bool
    {
        if (! $feligresId || ! $fechaSacramento) {
            return true;
        }

        $persona = \App\Models\Feligres::with('persona:id,fecha_nacimiento')->find($feligresId)?->persona;
        if (! $persona?->fecha_nacimiento) {
            return true;
        }

        try {
            if (\Carbon\Carbon::parse($fechaSacramento)->lt(\Carbon\Carbon::parse($persona->fecha_nacimiento))) {
                $this->addError($campo, "La fecha de {$label} no puede ser anterior a la fecha de nacimiento.");
                return false;
            }
        } catch (\Exception) {}

        return true;
    }

    private function validarGeneroRoles(): bool
    {
        $roles = [
            'padre'   => ['id' => $this->padre_feligres_id,   'esperado' => 'M', 'label' => 'El padre', 'campo' => 'padre_dni'],
            'madre'   => ['id' => $this->madre_feligres_id,   'esperado' => 'F', 'label' => 'La madre', 'campo' => 'madre_dni'],
            'padrino' => ['id' => $this->padrino_feligres_id, 'esperado' => 'M', 'label' => 'El padrino', 'campo' => 'padrino_dni'],
            'madrina' => ['id' => $this->madrina_feligres_id, 'esperado' => 'F', 'label' => 'La madrina', 'campo' => 'madrina_dni'],
        ];

        $valido = true;

        foreach ($roles as $info) {
            if (!$info['id']) {
                continue;
            }

            $feligres = \App\Models\Feligres::with('persona')->find($info['id']);
            if (!$feligres?->persona) {
                continue;
            }

            $sexo = $this->normalizarSexoCanonico($feligres->persona->sexo);

            if ($sexo !== null && $sexo !== $info['esperado']) {
                $this->addError($info['campo'], "{$info['label']} debe ser de sexo {$info['esperado']}.");
                $valido = false;
            }
        }

        return $valido;
    }

    private function normalizarSexoCanonico(?string $sexo): ?string
    {
        if ($sexo === null) {
            return null;
        }

        $s = strtoupper(trim($sexo));

        if (in_array($s, ['M', 'MASCULINO', 'HOMBRE', 'H', 'MALE'], true)) {
            return 'M';
        }

        if (in_array($s, ['F', 'FEMENINO', 'MUJER', 'W', 'FEMALE'], true)) {
            return 'F';
        }

        return null;
    }
}