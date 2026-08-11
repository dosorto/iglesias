<?php

namespace App\Livewire\Bautismo;

use App\Models\Bautismo;
use App\Models\Encargado;
use App\Models\Feligres;
use App\Models\Iglesias;
use App\Models\Persona;
use App\Models\TenantIglesia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BautismoEdit extends Component
{
    public Bautismo $bautismo;

    public ?int   $iglesia_id     = null;
    public ?int   $encargado_id   = null;
    public string $fecha_bautismo = '';
    public string $libro_bautismo = '';
    public string $folio          = '';
    public string $partida_numero = '';
    public string $observaciones  = '';
    public string $nota_marginal    = '';
    public string $ministro_celebrante = '';
    public string $parroco_celebrante  = '';
    public string $lugar_nacimiento = '';
    public string $lugar_celebracion = '';
    public string $exp_dia          = '';
    public string $exp_mes          = '';
    public string $exp_ano          = '';

    public ?array $encargado_info = null;

    public string $bautizado_dni         = '';
    public ?array $bautizado_persona     = null;
    public ?int   $bautizado_feligres_id = null;
    public string $bautizado_estado      = 'idle';

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
    public ?int   $ministro_feligres_id = null;
    public string $ministro_estado      = 'idle';

    public array   $busqueda_resultados = [];
    public ?string $busqueda_rol        = null;

    public ?string $mini_rol               = null;
    public ?string $mini_tipo              = null;
    public string  $mini_p_dni             = '';
    public string  $mini_p_primer_nombre   = '';
    public string  $mini_p_segundo_nombre  = '';
    public string  $mini_p_primer_apellido = '';
    public string  $mini_p_segundo_apellido= '';
    public string  $mini_p_fecha_nacimiento= '';
    public string  $mini_p_sexo            = '';
    public string  $mini_p_telefono        = '';
    public string  $mini_p_email           = '';
    public string  $mini_f_fecha_ingreso   = '';
    public string  $mini_f_estado          = 'Activo';

    public string $advertenciaDuplicado = '';
    public bool   $confirmarDuplicado   = false;

    public function mount(Bautismo $bautismo): void
    {
        $this->bautismo       = $bautismo;
        $this->iglesia_id     = session('tenant') ? TenantIglesia::currentId() : $bautismo->iglesia_id;
        $this->encargado_id   = $bautismo->encargado_id;
        $this->fecha_bautismo = $bautismo->fecha_bautismo?->format('Y-m-d') ?? '';
        $this->libro_bautismo = $bautismo->libro_bautismo ?? '';
        $this->folio          = $bautismo->folio ?? '';
        $this->partida_numero = $bautismo->partida_numero ?? '';
        $this->observaciones  = $bautismo->observaciones ?? '';
        $this->nota_marginal    = $bautismo->nota_marginal ?? '';
        $this->ministro_celebrante = $bautismo->ministro_celebrante ?? '';
        $this->parroco_celebrante  = $bautismo->parroco_celebrante ?? '';
        $this->lugar_nacimiento = $bautismo->lugar_nacimiento ?? '';
        $this->lugar_celebracion = $bautismo->lugar_celebracion ?? '';

        $fechaExp = $bautismo->fecha_expedicion;
        $this->exp_dia = $fechaExp?->day ? (string) $fechaExp->day : '';
        $this->exp_mes = $fechaExp?->month ? (string) $fechaExp->month : '';
        $this->exp_ano = $fechaExp?->year ? (string) $fechaExp->year : '';
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');

        $this->cargarEncargado();

        $this->cargarRolExistente('bautizado', $bautismo->bautizado_id);
        $this->cargarRolExistente('padre', $bautismo->padre_id);
        $this->cargarRolExistente('madre', $bautismo->madre_id);
        $this->cargarRolExistente('padrino', $bautismo->padrino_id);
        $this->cargarRolExistente('madrina', $bautismo->madrina_id);
        $this->cargarRolExistente('ministro', $bautismo->ministro_id);
    }

    private function resolverLugarCelebracion(): ?string
    {
        return trim($this->lugar_celebracion) !== '' ? trim($this->lugar_celebracion) : null;
    }

    private function cargarEncargado(): void
    {
        $encargado = null;

        if ($this->bautismo->encargado_id) {
            $encargado = Encargado::with('feligres.persona')->find($this->bautismo->encargado_id);
        }

        if (! $encargado) {
            $encargado = Encargado::activoParaIglesia(
                session('tenant') ? TenantIglesia::currentId() : $this->bautismo->iglesia_id
            );
        }

        if ($encargado?->feligres?->persona) {
            $persona = $encargado->feligres->persona;
            $this->encargado_info = [
                'encargado_id'    => $encargado->id,
                'feligres_id'     => $encargado->feligres->id,
                'nombre_completo' => $persona->nombre_completo,
                'dni'             => $persona->dni,
            ];
            $this->encargado_id = $encargado->id;
            return;
        }

        $this->encargado_info = null;
        $this->encargado_id = null;
    }

    private function cargarRolExistente(string $rol, ?int $feligresId): void
    {
        if (! $feligresId) {
            return;
        }

        $feligres = Feligres::with('persona')->find($feligresId);
        if (! $feligres || ! $feligres->persona) {
            return;
        }

        $persona = $feligres->persona;
        $this->{"{$rol}_persona"} = [
            'id'              => $persona->id,
            'dni'             => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono'        => $persona->telefono ?? null,
            'email'           => $persona->email ?? null,
        ];
        $this->{"{$rol}_feligres_id"} = $feligresId;
        $this->{"{$rol}_dni"}         = $persona->dni;
        $this->{"{$rol}_estado"}      = 'found';

        if ($rol === 'ministro') {
            $this->ministro_feligres_id = $feligresId;
        }
    }

    protected function rules(): array
    {
        $tenantIglesiaId = session('tenant') ? TenantIglesia::currentId() : null;

        return [
            'iglesia_id'     => array_filter([
                'required',
                'integer',
                'exists:iglesias,id',
                $tenantIglesiaId ? Rule::in([$tenantIglesiaId]) : null,
            ]),
            'encargado_id'   => ['required', 'integer', 'exists:encargado,id'],
            'fecha_bautismo' => ['required', 'date', 'before_or_equal:today'],
            'libro_bautismo' => ['required', 'string', 'max:100'],
            'folio'          => ['required', 'string', 'max:50'],
            'partida_numero' => ['required', 'string', 'max:50'],
            'observaciones'  => ['nullable', 'string', 'max:500'],
            'nota_marginal'    => ['nullable', 'string', 'max:500'],
            'ministro_celebrante' => ['nullable', 'string', 'max:150'],
            'parroco_celebrante'  => ['nullable', 'string', 'max:150'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:150'],
            'lugar_celebracion' => ['required', 'string', 'max:255'],
            'exp_dia'          => ['nullable', 'integer', 'min:1', 'max:31'],
            'exp_mes'          => ['nullable', 'integer', 'min:1', 'max:12'],
            'exp_ano'          => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:2100'],
            'bautizado_feligres_id' => ['required', 'integer', 'exists:feligres,id'],
            'padre_feligres_id'     => ['nullable', 'integer', 'exists:feligres,id'],
            'madre_feligres_id'     => ['nullable', 'integer', 'exists:feligres,id'],
            'padrino_feligres_id'   => ['nullable', 'integer', 'exists:feligres,id'],
            'madrina_feligres_id'   => ['nullable', 'integer', 'exists:feligres,id'],
            'ministro_feligres_id'  => ['nullable', 'integer', 'exists:feligres,id'],
        ];
    }

    protected function messages(): array
    {
        return [
            'iglesia_id.required'           => 'Debes seleccionar una iglesia.',
            'iglesia_id.exists'             => 'La iglesia seleccionada no existe.',
            'encargado_id.required'         => 'Debes seleccionar un encargado.',
            'encargado_id.exists'           => 'El encargado seleccionado no existe.',
            'fecha_bautismo.required'       => 'La fecha de bautismo es obligatoria.',
            'fecha_bautismo.date'           => 'La fecha de bautismo no es válida.',
            'fecha_bautismo.before_or_equal'=> 'La fecha de bautismo no puede ser futura.',
            'libro_bautismo.required'       => 'El libro de bautismo es obligatorio.',
            'libro_bautismo.max'            => 'El libro no puede superar los 100 caracteres.',
            'folio.required'                => 'El folio es obligatorio.',
            'folio.max'                     => 'El folio no puede superar los 50 caracteres.',
            'partida_numero.required'       => 'La partida es obligatoria.',
            'partida_numero.max'            => 'La partida no puede superar los 50 caracteres.',
            'observaciones.max'             => 'Las observaciones no pueden superar los 500 caracteres.',
            'nota_marginal.max'             => 'La nota marginal no puede superar los 500 caracteres.',
            'ministro_celebrante.max'        => 'El nombre del ministro celebrante no puede superar los 150 caracteres.',
            'parroco_celebrante.max'         => 'El párroco del momento no puede superar los 150 caracteres.',
            'lugar_nacimiento.max'          => 'El lugar de nacimiento no puede superar los 150 caracteres.',
            'lugar_celebracion.required'    => 'El lugar de la celebración es obligatorio.',
            'lugar_celebracion.max'         => 'El lugar de celebración no puede superar los 255 caracteres.',
            'exp_dia.min'                   => 'El día de expedición debe ser entre 1 y 31.',
            'exp_mes.min'                   => 'El mes de expedición debe ser entre 1 y 12.',
            'bautizado_feligres_id.required'=> 'Debes seleccionar un bautizado válido.',
            'bautizado_feligres_id.exists'  => 'El bautizado seleccionado no es válido.',
            'padre_feligres_id.exists'      => 'El padre seleccionado no es válido.',
            'madre_feligres_id.exists'      => 'La madre seleccionada no es válida.',
            'padrino_feligres_id.exists'    => 'El padrino seleccionado no es válido.',
            'madrina_feligres_id.exists'    => 'La madrina seleccionada no es válida.',
            'ministro_feligres_id.exists'   => 'El sacerdote que bautizó seleccionado no es válido.',
        ];
    }

    public function updated(string $field): void
    {
        if (array_key_exists($field, $this->rules())) {
            $this->validateOnly($field);
        }
    }

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
                $q->where('primer_nombre', 'like', $term)
                    ->orWhere('segundo_nombre', 'like', $term)
                    ->orWhere('primer_apellido', 'like', $term)
                    ->orWhere('segundo_apellido', 'like', $term);
            })->orderBy('primer_apellido')->orderBy('primer_nombre')->limit(15)->get();
        }

        if ($personas->isEmpty()) {
            $this->{"{$rol}_persona"}     = null;
            $this->{"{$rol}_feligres_id"} = null;
            $this->{"{$rol}_estado"}      = 'sin_persona';
            $this->busqueda_resultados      = [];
            $this->busqueda_rol             = null;
            return;
        }

        if ($personas->count() === 1) {
            $this->asignarPersonaARol($rol, $personas->first());
            return;
        }

        if ($rol === 'bautizado') {
            $yaBautizadoFeligresIds = Bautismo::where('id', '!=', $this->bautismo->id)
                ->whereNotNull('bautizado_id')
                ->pluck('bautizado_id')
                ->toArray();

            $personasBautizadas = Feligres::whereIn('id', $yaBautizadoFeligresIds)
                ->pluck('id_persona')
                ->toArray();

            $personas = $personas->whereNotIn('id', $personasBautizadas);

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

        $this->busqueda_rol      = $rol;
        $this->{"{$rol}_estado"} = 'multiples';
    }

    public function seleccionarResultado(int $personaId): void
    {
        $rol = $this->busqueda_rol;
        if (! $rol) {
            return;
        }

        $persona = Persona::findOrFail($personaId);
        $this->asignarPersonaARol($rol, $persona);
    }

    private function asignarPersonaARol(string $rol, Persona $persona): void
    {
        $roles = ['bautizado', 'padre', 'madre', 'padrino', 'madrina', 'ministro'];
        $labels = [
            'bautizado' => 'Bautizado',
            'padre'     => 'Padre',
            'madre'     => 'Madre',
            'padrino'   => 'Padrino',
            'madrina'   => 'Madrina',
            'ministro'  => 'Sacerdote que bautizó',
        ];

        foreach ($roles as $r) {
            if ($r === $rol) {
                continue;
            }
            $existente = $this->{"{$r}_persona"};
            if ($existente && $existente['id'] === $persona->id) {
                $this->addError("{$rol}_dni", "Esta persona ya está asignada como {$labels[$r]}.");
                return;
            }
        }

        $feligres = Feligres::where('id_persona', $persona->id)->first();

        if ($rol === 'bautizado' && $feligres) {
            $yaBautizado = Bautismo::where('id', '!=', $this->bautismo->id)
                ->where('bautizado_id', $feligres->id)
                ->exists();
            if ($yaBautizado) {
                $this->addError("{$rol}_dni", 'Esta persona ya fue bautizada anteriormente.');
                return;
            }
        }

        $this->{"{$rol}_persona"} = [
            'id'              => $persona->id,
            'dni'             => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono'        => $persona->telefono ?? null,
            'email'           => $persona->email ?? null,
        ];
        $this->{"{$rol}_dni"} = $persona->dni;

        // El ministro (sacerdote que bautizó) no necesita ser feligrés obligatoriamente
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
        $this->{"{$rol}_dni"}         = '';
        $this->{"{$rol}_persona"}     = null;
        $this->{"{$rol}_feligres_id"} = null;
        $this->{"{$rol}_estado"}      = 'idle';

        if ($rol === 'ministro') {
            $this->ministro_feligres_id = null;
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
                'sexo'             => $this->mini_p_sexo,
                'telefono'         => $this->mini_p_telefono ?: null,
                'email'            => $this->mini_p_email ?: null,
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
                'email'           => $persona->email ?? null,
            ];

            if ($rol === 'ministro') {
                $this->ministro_feligres_id = $feligres->id;
            } else {
                $this->{"{$rol}_feligres_id"} = $feligres->id;
            }

            $this->{"{$rol}_estado"}      = 'found';
            $this->{"{$rol}_dni"}         = $persona->dni;
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

        $rol = $this->mini_rol;
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

        $this->{"{$rol}_estado"}      = 'found';

        $this->cancelarMini();
    }

    public function guardar(): void
    {
        if (session('tenant')) {
            $this->iglesia_id = TenantIglesia::currentId();
        }

        $this->cargarEncargado();
        $this->encargado_id = $this->encargado_info['encargado_id'] ?? null;

        $this->validate();

        if (!$this->validarGeneroRoles()) {
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

        $this->guardarBautismo($fechaExp);
    }

    private function guardarBautismo($fechaExp): void
    {
        $lugarCelebracion = $this->resolverLugarCelebracion();
        $this->lugar_celebracion = $lugarCelebracion ?? '';

        $this->bautismo->update([
            'iglesia_id'     => $this->iglesia_id,
            'encargado_id'   => $this->encargado_id,
            'fecha_bautismo' => $this->fecha_bautismo,
            'bautizado_id'   => $this->bautizado_feligres_id,
            'padre_id'       => $this->padre_feligres_id,
            'madre_id'       => $this->madre_feligres_id,
            'padrino_id'     => $this->padrino_feligres_id,
            'madrina_id'     => $this->madrina_feligres_id,
            'ministro_id'    => $this->ministro_feligres_id,
            'libro_bautismo' => trim($this->libro_bautismo),
            'folio'          => trim($this->folio),
            'partida_numero' => trim($this->partida_numero),
            'observaciones'  => $this->observaciones ?: null,
            'nota_marginal'    => $this->nota_marginal ?: null,
            'ministro_celebrante' => $this->ministro_celebrante ?: null,
            'parroco_celebrante'  => $this->parroco_celebrante ?: null,
            'lugar_nacimiento' => $this->lugar_nacimiento ?: null,
            'lugar_celebracion' => $lugarCelebracion,
            'fecha_expedicion' => $fechaExp,
        ]);

        session()->flash('success', 'Bautismo actualizado correctamente.');
        $this->redirect(route('bautismo.index'), navigate: false);
    }

    public function render()
    {
        if (session('tenant')) {
            $iglesias = TenantIglesia::query()->where('id', TenantIglesia::currentId())->get();
        } else {
            $centralConn = config('tenancy.central_connection', 'mysql');
            $iglesias = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();
        }
        $iglesiaActual = $iglesias->firstWhere('id', $this->iglesia_id);

        return view('livewire.bautismo.bautismo-edit', compact('iglesias', 'iglesiaActual'));
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
            'padre'    => ['id' => $this->padre_feligres_id,   'esperado' => 'M', 'label' => 'El padre', 'campo' => 'padre_dni'],
            'madre'    => ['id' => $this->madre_feligres_id,   'esperado' => 'F', 'label' => 'La madre', 'campo' => 'madre_dni'],
            'padrino'  => ['id' => $this->padrino_feligres_id, 'esperado' => 'M', 'label' => 'El padrino', 'campo' => 'padrino_dni'],
            'madrina'  => ['id' => $this->madrina_feligres_id, 'esperado' => 'F', 'label' => 'La madrina', 'campo' => 'madrina_dni'],
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
