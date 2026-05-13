<?php

namespace App\Livewire\Bautismo;

use Livewire\Component;
use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Iglesias;
use App\Models\TenantIglesia;
use App\Models\Encargado;
use App\Models\Bautismo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BautismoCreate extends Component
{
    // Wizard
    public int $paso = 1;

    // Paso 1
    public $iglesia_id     = null;
    public string $fecha_bautismo = '';
    public $encargado_id   = null;

    // Paso 2  roles
    public string $bautizado_dni         = '';
    public ?array $bautizado_persona     = null;
    public ?int   $bautizado_feligres_id = null;
    public string $bautizado_estado      = 'idle';

    // Busqueda con resultados multiples (compartido)
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

    // Paso 3
    public string $libro_bautismo = '';
    public string $folio          = '';
    public string $partida_numero = '';
    public string $observaciones  = '';
    public string $nota_marginal    = '';
    public string $parroco_celebrante = '';
    public string $lugar_nacimiento = '';
    public string $lugar_expedicion = '';
    public string $exp_dia          = '';
    public string $exp_mes          = '';
    public string $exp_ano          = '';

    public function mount(): void
    {
        $this->fecha_bautismo       = now()->format('Y-m-d');
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');

        $this->iglesia_id = TenantIglesia::currentId();
        $this->aplicarLugarExpedicionPorDefecto();

        // Encargado por defecto: primer encargado disponible
        $encargadoDefault   = Encargado::with('feligres.persona')->where('estado', 'Activo')->first();
        $this->encargado_id = $encargadoDefault?->id;
    }

    private function aplicarLugarExpedicionPorDefecto(): void
    {
        if (trim($this->lugar_expedicion) !== '') {
            return;
        }

        $direccion = '';

        if (session('tenant')) {
            $direccion = trim((string) (TenantIglesia::current()?->direccion ?? ''));
        } elseif ($this->iglesia_id) {
            $direccion = trim((string) (Iglesias::query()->find($this->iglesia_id)?->direccion ?? ''));
        }

        if ($direccion !== '') {
            $this->lugar_expedicion = $direccion;
        }
    }

    // Navegacion

    public function siguientePaso(): void
    {
        if ($this->paso === 1) {
            if (! $this->bautizado_feligres_id) {
                $this->addError('bautizado_dni', 'El bautizado es obligatorio y debe estar registrado como feligres.');
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

        // Multiples resultados — mostrar lista para seleccionar
        // Para el bautizado, excluir personas que ya fueron bautizadas
        if ($rol === 'bautizado') {
            $bautizadoFeligresIds = Bautismo::pluck('bautizado_id')->toArray();
            $feligresPersonaIds   = Feligres::whereIn('id', $bautizadoFeligresIds)->pluck('id_persona')->toArray();
            $personas             = $personas->whereNotIn('id', $feligresPersonaIds);

            if ($personas->isEmpty()) {
                $this->{"{$rol}_estado"} = 'sin_persona';
                $this->addError("{$rol}_dni", 'No se encontraron personas disponibles para ser bautizadas (todas ya fueron bautizadas).');
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

    // Seleccionar un resultado de la lista

    public function seleccionarResultado(int $personaId): void
    {
        $rol = $this->busqueda_rol;
        if (! $rol) return;

        $persona = Persona::findOrFail($personaId);
        $this->asignarPersonaARol($rol, $persona);
    }

    // Asignar persona a un rol con validacion de duplicados

    private function asignarPersonaARol(string $rol, Persona $persona): void
    {
        $roles = ['bautizado', 'padre', 'madre', 'padrino', 'madrina'];
        $labels = [
            'bautizado' => 'Bautizado',
            'padre'     => 'Padre',
            'madre'     => 'Madre',
            'padrino'   => 'Padrino',
            'madrina'   => 'Madrina',
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

        // Validar que el bautizado no haya sido bautizado anteriormente
        if ($rol === 'bautizado' && $feligres) {
            $yaBautizado = Bautismo::where('bautizado_id', $feligres->id)->exists();
            if ($yaBautizado) {
                $this->addError("{$rol}_dni", "Esta persona ya fue bautizada anteriormente.");
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

    // Limpiar un rol

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

    // Mini-form: abrir Crear Persona

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

    // Mini-form: abrir Registrar como Feligres

    public function abrirRegistrarFeligres(string $rol): void
    {
        $this->mini_rol             = $rol;
        $this->mini_tipo            = 'feligres';
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
        $this->mini_f_estado        = 'Activo';

        $this->resetErrorBag();
    }

    // Mini-form: cancelar

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

    // Mini-form: guardar nueva persona + feligres (transaccion atomica)

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
            'mini_p_dni.required'             => 'El número de identidad es obligatorio.',
            'mini_p_dni.min'                  => 'El DNI debe tener al menos 8 caracteres.',
            'mini_p_dni.unique'               => 'Ya existe una persona con ese DNI.',
            'mini_p_primer_nombre.required'   => 'El primer nombre es obligatorio.',
            'mini_p_primer_nombre.regex'      => 'El primer nombre solo puede contener letras, espacios, guiones y apóstrofes.',
            'mini_p_primer_apellido.required' => 'El primer apellido es obligatorio.',
            'mini_p_primer_apellido.regex'    => 'El primer apellido solo puede contener letras, espacios, guiones y apóstrofes.',
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
                'primer_nombre'    => Str::title($this->mini_p_primer_nombre),
                'segundo_nombre'   => $this->mini_p_segundo_nombre ? Str::title($this->mini_p_segundo_nombre) : null,
                'primer_apellido'  => Str::title($this->mini_p_primer_apellido),
                'segundo_apellido' => $this->mini_p_segundo_apellido ? Str::title($this->mini_p_segundo_apellido) : null,
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

            $this->{"{$rol}_feligres_id"} = $feligres->id;
            $this->{"{$rol}_estado"}      = 'found';
            $this->{"{$rol}_dni"}         = $persona->dni;
        });

        $this->cancelarMini();
    }

    // Mini-form: registrar persona existente como feligres

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

    // Guardar bautismo final

    public function guardar(): void
    {
        if (session('tenant')) {
            $this->iglesia_id = TenantIglesia::currentId();
        }

        $this->validate([
            'fecha_bautismo' => ['required', 'date'],
            'nota_marginal'    => ['nullable', 'string', 'max:500'],
            'parroco_celebrante' => ['nullable', 'string', 'max:150'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:150'],
            'lugar_expedicion' => ['nullable', 'string', 'max:150'],
            'exp_dia'          => ['nullable', 'integer', 'min:1', 'max:31'],
            'exp_mes'          => ['nullable', 'integer', 'min:1', 'max:12'],
            'exp_ano'          => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:2100'],
        ], [
            'fecha_bautismo.required' => 'La fecha de bautismo es obligatoria.',
            'fecha_bautismo.date'     => 'La fecha de bautismo no es válida.',
            'nota_marginal.max'       => 'La nota marginal no puede superar los 500 caracteres.',
            'parroco_celebrante.max'  => 'El nombre del párroco celebrante no puede superar los 150 caracteres.',
            'lugar_nacimiento.max'    => 'El lugar de nacimiento no puede superar los 150 caracteres.',
            'lugar_expedicion.max'    => 'El lugar no puede superar los 150 caracteres.',
            'exp_dia.min'             => 'El día debe ser entre 1 y 31.',
            'exp_mes.min'             => 'El mes debe ser entre 1 y 12.',
        ]);

        if (! $this->bautizado_feligres_id) {
            $this->addError('bautizado_dni', 'El bautizado es obligatorio.');
            return;
        }

        if (! $this->validarGeneroRoles()) {
            return;
        }

        if (! $this->validarFechaPosteriorNacimiento(
            $this->bautizado_feligres_id,
            $this->fecha_bautismo,
            'fecha_bautismo',
            'bautismo'
        )) {
            return;
        }

        $fechaExp = $this->resolverFechaExpedicion();
        if ($fechaExp === false) {
            return;
        }

        Bautismo::create([
            'iglesia_id'     => $this->iglesia_id,
            'fecha_bautismo' => $this->fecha_bautismo,
            'encargado_id'   => $this->encargado_id ?: null,
            'bautizado_id'   => $this->bautizado_feligres_id,
            'padre_id'       => $this->padre_feligres_id,
            'madre_id'       => $this->madre_feligres_id,
            'padrino_id'     => $this->padrino_feligres_id,
            'madrina_id'     => $this->madrina_feligres_id,
            'libro_bautismo' => $this->libro_bautismo ?: null,
            'folio'          => $this->folio          ?: null,
            'partida_numero' => $this->partida_numero ?: null,
            'observaciones'  => $this->observaciones  ?: null,
            'nota_marginal'    => $this->nota_marginal    ?: null,
            'parroco_celebrante' => $this->parroco_celebrante ?: null,
            'lugar_nacimiento' => $this->lugar_nacimiento ?: null,
            'lugar_expedicion' => $this->lugar_expedicion ?: null,
            'fecha_expedicion' => $fechaExp,
        ]);

        session()->flash('success', 'Bautismo registrado correctamente.');
        $this->redirect(route('bautismo.index'), navigate: false);
    }

    public function render()
    {
        $centralConn = config('tenancy.central_connection', 'mysql');

        if (session('tenant')) {
            $iglesias = collect([TenantIglesia::current()])->filter();
        } else {
            $iglesias = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();
        }

        return view('livewire.bautismo.bautismo-create', [
            'iglesias'   => $iglesias,
            'encargados' => Encargado::with('feligres.persona')->get(),
        ]);
    }

    private function resolverFechaExpedicion(): string|false|null
    {
        $dia = trim((string) $this->exp_dia);
        $mes = trim((string) $this->exp_mes);
        $ano = trim((string) $this->exp_ano);

        if ($dia === '' && $mes === '' && $ano === '') {
            return null;
        }

        if ($dia === '' || $mes === '' || $ano === '') {
            $this->addError('exp_dia', 'Para la fecha de expedición debes completar día, mes y año.');
            return false;
        }

        $year = (int) $ano;

        if (! checkdate((int) $mes, (int) $dia, $year)) {
            $this->addError('exp_dia', 'La fecha de expedición no es válida.');
            return false;
        }

        return sprintf('%04d-%02d-%02d', $year, (int) $mes, (int) $dia);
    }

    private function validarGeneroRoles(): bool
    {
        $roles = [
            'padre'   => ['id' => $this->padre_feligres_id,   'esperado' => 'M', 'campo' => 'padre_dni',   'label' => 'El padre'],
            'madre'   => ['id' => $this->madre_feligres_id,   'esperado' => 'F', 'campo' => 'madre_dni',   'label' => 'La madre'],
            'padrino' => ['id' => $this->padrino_feligres_id, 'esperado' => 'M', 'campo' => 'padrino_dni', 'label' => 'El padrino'],
            'madrina' => ['id' => $this->madrina_feligres_id, 'esperado' => 'F', 'campo' => 'madrina_dni', 'label' => 'La madrina'],
        ];

        $valido = true;
        foreach ($roles as $config) {
            if (! $config['id']) {
                continue;
            }

            $sexo = $this->normalizarSexoCanonico(
                Feligres::with('persona:id,sexo')->find($config['id'])?->persona?->sexo
            );

            if ($sexo && $sexo !== $config['esperado']) {
                $genero = $config['esperado'] === 'M' ? 'masculino' : 'femenino';
                $this->addError($config['campo'], "{$config['label']} debe ser de género {$genero}.");
                $valido = false;
            }
        }

        return $valido;
    }

    private function normalizarSexoCanonico(?string $sexo): ?string
    {
        if (! $sexo) {
            return null;
        }
        $valor = mb_strtolower(trim($sexo));
        if (in_array($valor, ['m', 'masculino', 'hombre'], true)) {
            return 'M';
        }
        if (in_array($valor, ['f', 'femenino', 'mujer'], true)) {
            return 'F';
        }
        return null;
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
}