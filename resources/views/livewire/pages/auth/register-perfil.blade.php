<?php

use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Encargado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.guest')] class extends Component
{
    use WithFileUploads;

    public int $step = 1;

    // --- Paso 1: Datos Persona ---
    public string $primer_nombre = '';
    public string $segundo_nombre = '';
    public string $primer_apellido = '';
    public string $segundo_apellido = '';
    public string $fecha_nacimiento = '';
    public string $sexo = '';
    public string $telefono = '';
    public string $dni = '';
    public string $email = '';

    // --- Paso 2: Encargado / Firma ---
    public $path_firma_principal = null;

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validateStepOne();
        }

        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step--;
    }

    public function register(): void
    {
        $this->validateStepOne();
        $this->validateStepTwo();

        $firmaPath = null;
        if ($this->path_firma_principal) {
            $firmaPath = $this->path_firma_principal->store('firmas', 'public');
        }

        DB::transaction(function () use ($firmaPath) {
            $persona = Persona::create([
                'primer_nombre'    => $this->primer_nombre,
                'segundo_nombre'   => $this->segundo_nombre ?: null,
                'primer_apellido'  => $this->primer_apellido,
                'segundo_apellido' => $this->segundo_apellido ?: null,
                'fecha_nacimiento' => $this->fecha_nacimiento ?: null,
                'sexo'             => $this->sexo ?: null,
                'telefono'         => $this->telefono ?: null,
                'dni'              => $this->dni ?: null,
                'email'            => $this->email ?: null,
            ]);

            $feligres = Feligres::create([
                'id_persona'    => $persona->id,
                'id_iglesia'    => DB::table('iglesias')->first()->id,
                'fecha_ingreso' => now()->toDateString(),
                'estado'        => 'Activo',
            ]);

            Encargado::create([
                'id_feligres'          => $feligres->id,
                'path_firma_principal' => $firmaPath,
            ]);
        });

        session()->forget('pending_encargado_registration');
        session()->flash('success', 'Encargado registrado exitosamente.');
        $this->redirect(route('dashboard'), navigate: true);
    }

   private function validateStepOne(): void
{
    $this->validate([
        'primer_nombre'    => ['required', 'string', 'min:2', 'max:150'],
        'segundo_nombre'   => ['nullable', 'string', 'max:150'],
        'primer_apellido'  => ['required', 'string', 'min:2', 'max:100'],
        'segundo_apellido' => ['nullable', 'string', 'max:100'],
        'fecha_nacimiento' => ['required', 'date', 'before:today'],
        'sexo'             => ['required', 'in:M,F'],
        'telefono'         => ['required', 'string', 'regex:/^[0-9\+\-\s]+$/', 'min:8', 'max:20'],
        'dni'              => ['required', 'string', 'regex:/^[0-9]+$/', 'min:8', 'max:20'],
        'email'            => [
            'required',
            'email',
            'max:100',
            function (string $attribute, mixed $value, \Closure $fail): void {
                if ($this->correoPerteneceAOtroEncargado((string) $value)) {
                    $fail('Este correo ya está asignado a otro encargado. Usa uno distinto para evitar problemas al iniciar sesión.');
                }
            },
        ],
    ], [
        'primer_nombre.required'    => 'El primer nombre es obligatorio.',
        'primer_apellido.required'  => 'El primer apellido es obligatorio.',
        'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
        'fecha_nacimiento.before'   => 'La fecha de nacimiento debe ser anterior a hoy.',
        'sexo.required'             => 'El sexo es obligatorio.',
        'sexo.in'                   => 'Selecciona Masculino o Femenino.',
        'telefono.required'         => 'El teléfono es obligatorio.',
        'telefono.regex'            => 'El teléfono solo puede contener números.',
        'telefono.min'              => 'El teléfono debe tener al menos 8 dígitos.',
        'dni.required'              => 'El número de identidad es obligatorio.',
        'dni.regex'                 => 'El DNI solo puede contener números, sin letras.',
        'dni.min'                   => 'El DNI debe tener al menos 8 dígitos.',
        'email.required'            => 'El correo electrónico del encargado es obligatorio.',
        'email.email'               => 'El correo electrónico no es válido.',
    ]);
}

    private function correoPerteneceAOtroEncargado(?string $email): bool
    {
        $normalizedEmail = Str::lower(trim((string) $email));

        if ($normalizedEmail === '') {
            return false;
        }

        return Encargado::query()
            ->whereHas('feligres.persona', function ($query) use ($normalizedEmail) {
                $query->whereRaw('LOWER(email) = ?', [$normalizedEmail]);
            })
            ->exists();
    }

    private function validateStepTwo(): void
    {
        $this->validate([
            'path_firma_principal' => ['nullable', 'image', 'max:2048'],
        ], [
            'path_firma_principal.image' => 'El archivo debe ser una imagen.',
            'path_firma_principal.max'   => 'La imagen no debe superar los 2MB.',
        ]);
    }
}; ?>

<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900">Registrar Encargado</h1>
        <p class="mt-2 text-sm text-gray-600">Completa los 2 pasos para registrar un nuevo encargado.</p>
    </div>

    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 p-3 text-sm text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stepper --}}
    <div class="flex items-center gap-2">
        @foreach ([1 => 'Persona', 2 => 'Firma'] as $num => $label)
            <div class="flex items-center gap-2 {{ $num < 2 ? 'flex-1' : '' }}">
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-semibold
                    {{ $step > $num ? 'bg-green-500 text-white' : ($step === $num ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500') }}">
                    {{ $step > $num ? '✓' : $num }}
                </span>
                <span class="text-sm {{ $step >= $num ? 'text-gray-900 font-medium' : 'text-gray-400' }}">{{ $label }}</span>
            </div>
            @if ($num < 2)
                <div class="h-px flex-1 bg-gray-200"></div>
            @endif
        @endforeach
    </div>

    {{-- PASO 1: Datos Personales --}}
    @if ($step === 1)
        <form wire:submit="nextStep" class="space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Datos Personales</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="primer_nombre" value="Primer Nombre *" />
                    <x-text-input wire:model="primer_nombre" id="primer_nombre" class="mt-1 block w-full" type="text" required autofocus />
                    <x-input-error :messages="$errors->get('primer_nombre')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="segundo_nombre" value="Segundo Nombre" />
                    <x-text-input wire:model="segundo_nombre" id="segundo_nombre" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('segundo_nombre')" class="mt-1" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="primer_apellido" value="Primer Apellido *" />
                    <x-text-input wire:model="primer_apellido" id="primer_apellido" class="mt-1 block w-full" type="text" required />
                    <x-input-error :messages="$errors->get('primer_apellido')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="segundo_apellido" value="Segundo Apellido" />
                    <x-text-input wire:model="segundo_apellido" id="segundo_apellido" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('segundo_apellido')" class="mt-1" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="fecha_nacimiento" value="Fecha de Nacimiento *" />
                    <x-text-input wire:model="fecha_nacimiento" id="fecha_nacimiento" class="mt-1 block w-full" type="date" />
                    <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="sexo" value="Sexo *" />
                    <select wire:model="sexo" id="sexo"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">-- Seleccionar --</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                    <x-input-error :messages="$errors->get('sexo')" class="mt-1" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="dni" value="DNI / Documento *" />
                    <x-text-input wire:model="dni" id="dni" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('dni')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="telefono" value="Teléfono *" />
                    <x-text-input wire:model="telefono" id="telefono" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('telefono')" class="mt-1" />
                </div>
            </div>

            <div>
                <x-input-label for="email" value="Correo Electrónico *" />
                <x-text-input wire:model="email" id="email" class="mt-1 block w-full" type="email" required />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="flex justify-end">
                <x-primary-button>Siguiente →</x-primary-button>
            </div>
        </form>
    @endif

    {{-- PASO 2: Firma --}}
    {{-- PASO 2: Firma --}}
    @if ($step === 2)
    <form wire:submit="register" class="space-y-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Firma del Encargado</p>

        {{-- Resumen --}}
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700 space-y-1">
            <div>Persona: <span class="font-semibold">{{ $primer_nombre }} {{ $primer_apellido }}</span></div>
            @if($dni) <div>DNI: <span class="font-semibold">{{ $dni }}</span></div> @endif
            @if($fecha_nacimiento) <div>Nacimiento: <span class="font-semibold">{{ $fecha_nacimiento }}</span></div> @endif
        </div>

        {{-- Upload Area --}}
        <div>
            <x-input-label value="Imagen de Firma (opcional)" class="mb-2" />

            <label for="path_firma_principal"
                   class="group relative flex flex-col items-center justify-center w-full
                          rounded-xl border-2 border-dashed cursor-pointer transition-all duration-200
                          {{ $path_firma_principal
                              ? 'border-green-400 bg-green-50 dark:bg-green-900/10'
                              : 'border-gray-300 bg-gray-50 hover:border-blue-400 hover:bg-blue-50 dark:bg-gray-700/40 dark:border-gray-600 dark:hover:border-blue-500' }}
                          min-h-[180px] p-6">

                @if ($path_firma_principal)
                    {{-- Vista previa --}}
                    <div class="flex flex-col items-center gap-3 w-full">
                        <img src="{{ $path_firma_principal->temporaryUrl() }}"
                             alt="Vista previa firma"
                             class="max-h-36 w-auto rounded-lg border border-green-200 object-contain shadow-sm" />
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm font-medium text-green-600">Imagen cargada correctamente</span>
                        </div>
                        <span class="text-xs text-gray-400 underline group-hover:text-blue-500 transition-colors">
                            Haz clic para cambiar la imagen
                        </span>
                    </div>
                @else
                    {{-- Estado vacío --}}
                    <div class="flex flex-col items-center gap-3 text-center">
                        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center
                                    group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-500 transition-colors"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M3 17v2a2 2 0 002 2h14a2 2 0 002-2v-2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-blue-600 transition-colors">
                                Haz clic para subir la firma
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                o arrastra y suelta la imagen aquí
                            </p>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap justify-center">
                            <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">PNG</span>
                            <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">JPG</span>
                            <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">JPEG</span>
                            <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">Máx. 2MB</span>
                        </div>
                    </div>
                @endif

                <input
                    wire:model="path_firma_principal"
                    id="path_firma_principal"
                    type="file"
                    accept="image/*"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
            </label>

            {{-- Loading --}}
            <div wire:loading wire:target="path_firma_principal"
                 class="mt-2 flex items-center gap-2 text-sm text-blue-600">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                Cargando imagen...
            </div>

            <x-input-error :messages="$errors->get('path_firma_principal')" class="mt-1" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <x-secondary-button type="button" wire:click="previousStep">
                ← Volver
            </x-secondary-button>
            <x-primary-button>
                <span wire:loading.remove wire:target="register">Registrar Encargado</span>
                <span wire:loading wire:target="register" class="flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    Guardando...
                </span>
            </x-primary-button>
        </div>
    </form>
@endif
</div>
