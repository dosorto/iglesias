<?php

namespace App\Livewire\PrimeraComunion;

use Livewire\Component;
use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Iglesias;
use App\Models\PrimeraComunion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PrimeraComunionCreate extends Component
{
    // Wizard
    public int $paso = 1;

    // Paso 1
    public $iglesia_id                  = null;
    public string $fecha_primera_comunion = '';

    // Paso 2 - roles
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

    // Paso 3
    public string $libro_comunion = '';
    public string $folio          = '';
    public string $partida_numero = '';
    public string $observaciones  = '';

   public function mount(): void
{
    $this->fecha_primera_comunion = now()->format('Y-m-d');
    $this->mini_f_fecha_ingreso   = now()->format('Y-m-d');

    // En tenant, tomar el id de la iglesia local automáticamente
    if (session('tenant')) {
        $iglesiaLocal     = DB::table('iglesias')->first();
        $this->iglesia_id = $iglesiaLocal?->id;
    }
}
    // Navegacion

    public function siguientePaso(): void
    {
        if ($this->paso === 1) {
            $this->validate([
                'iglesia_id'             => ['required'],
                'fecha_primera_comunion' => ['required', 'date'],
            ], [
                'iglesia_id.required'             => 'Selecciona la iglesia.',
                'fecha_primera_comunion.required' => 'La fecha de primera comunión es obligatoria.',
            ]);
        }

        if ($this->paso === 2) {
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

    // Buscar persona por DNI

    public function buscarPersona(string $rol): void
    {
        $dni = trim($this->{"{$rol}_dni"});

        if (empty($dni)) {
            $this->addError("{$rol}_dni", 'Ingresa un DNI para buscar.');
            return;
        }

        if (! ctype_digit($dni)) {
            $this->addError("{$rol}_dni", 'El DNI solo puede contener números.');
            return;
        }

        $persona = Persona::where('dni', $dni)->first();

        if (! $persona) {
            $this->{"{$rol}_persona"}     = null;
            $this->{"{$rol}_feligres_id"} = null;
            $this->{"{$rol}_estado"}      = 'sin_persona';
            return;
        }

        $feligres = Feligres::where('id_persona', $persona->id)->first();

        $this->{"{$rol}_persona"} = [
            'id'              => $persona->id,
            'dni'             => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono'        => $persona->telefono ?? null,
            'email'           => $persona->email    ?? null,
        ];

        if ($feligres) {
            $this->{"{$rol}_feligres_id"} = $feligres->id;
            $this->{"{$rol}_estado"}      = 'found';
        } else {
            $this->{"{$rol}_feligres_id"} = null;
            $this->{"{$rol}_estado"}      = 'sin_feligres';
        }

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

    // Mini-form: guardar nueva persona + feligres

    public function guardarMiniPersona(): void
    {
        $this->validate([
            'mini_p_dni'              => ['required', 'string', 'min:8', 'max:20', Rule::unique('personas', 'dni')],
            'mini_p_primer_nombre'    => ['required', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_primer_apellido'  => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_segundo_nombre'   => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_segundo_apellido' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'mini_p_sexo'             => ['nullable', 'in:M,F'],
            'mini_p_telefono'         => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-]+$/'],
            'mini_p_email'            => ['nullable', 'email', 'max:255'],
            'mini_f_fecha_ingreso'    => ['nullable', 'date'],
            'mini_f_estado'           => ['required', 'in:Activo,Inactivo'],
        ], [
            'mini_p_dni.required'                  => 'El numero de identidad es obligatorio.',
            'mini_p_dni.min'                       => 'El DNI debe tener al menos 8 caracteres.',
            'mini_p_dni.unique'                    => 'Ya existe una persona con ese DNI.',
            'mini_p_primer_nombre.required'        => 'El primer nombre es obligatorio.',
            'mini_p_primer_nombre.regex'           => 'El primer nombre solo puede contener letras, espacios, guiones y apóstrofes.',
            'mini_p_primer_apellido.required'      => 'El primer apellido es obligatorio.',
            'mini_p_primer_apellido.regex'         => 'El primer apellido solo puede contener letras, espacios, guiones y apóstrofes.',
            'mini_p_segundo_nombre.regex'          => 'El segundo nombre solo puede contener letras, espacios, guiones y apóstrofes.',
            'mini_p_segundo_apellido.regex'        => 'El segundo apellido solo puede contener letras, espacios, guiones y apóstrofes.',
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
                'sexo'             => $this->mini_p_sexo             ?: null,
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
            'iglesia_id.required' => 'La iglesia (Paso 1) debe estar seleccionada.',
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

    // Guardar primera comunion final

    public function guardar(): void
    {
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
        $iglesias = collect([DB::table('iglesias')->first()])->filter();
    } else {
        $iglesias = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();
    }

    return view('livewire.primera-comunion.primera-comunion-create', [
        'iglesias' => $iglesias,
    ]);
}
}