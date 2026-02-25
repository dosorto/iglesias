<?php

use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Encargado;
use Illuminate\Support\Facades\DB;
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

    // --- Paso 2: Feligrés seleccionado ---
    public string $id_feligres = '';

    // --- Paso 3: Encargado / Firma ---
    public $path_firma_principal = null;

    public function getFeligresesDisponiblesProperty()
    {
        return Feligres::with('persona')
            ->where('estado', 'Activo')
            ->get();
    }

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
        dd(session('tenant'), config('database.default'));
        $this->validateStepOne();
        $this->validateStepThree();

        $primerNombre    = $this->primer_nombre;
        $segundoNombre   = $this->segundo_nombre ?: null;
        $primerApellido  = $this->primer_apellido;
        $segundoApellido = $this->segundo_apellido ?: null;
        $fechaNacimiento = $this->fecha_nacimiento ?: null;
        $sexo            = $this->sexo ?: null;
        $telefono        = $this->telefono ?: null;
        $dni             = $this->dni ?: null;
        $email           = $this->email ?: null;
        $idFeligres      = $this->id_feligres;

        $firmaPath = null;
        if ($this->path_firma_principal) {
            $firmaPath = $this->path_firma_principal->store('firmas', 'public');
        }

        DB::transaction(function () use (
            $primerNombre, $segundoNombre, $primerApellido, $segundoApellido,
            $fechaNacimiento, $sexo, $telefono, $dni, $email,
            $idFeligres, $firmaPath
        ) {
            $persona = Persona::create([
                'primer_nombre'    => $primerNombre,
                'segundo_nombre'   => $segundoNombre,
                'primer_apellido'  => $primerApellido,
                'segundo_apellido' => $segundoApellido,
                'fecha_nacimiento' => $fechaNacimiento,
                'sexo'             => $sexo,
                'telefono'         => $telefono,
                'dni'              => $dni,
                'email'            => $email,
            ]);

            Encargado::create([
                'id_feligres'          => $idFeligres,
                'path_firma_principal' => $firmaPath,
            ]);
        });

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
            'fecha_nacimiento' => ['nullable', 'date'],
            'sexo'             => ['nullable', 'in:M,F'],
            'telefono'         => ['nullable', 'string', 'max:20'],
            'dni'              => ['nullable', 'string'],
            'email'            => ['nullable', 'email', 'max:100'],
        ], [
            'primer_nombre.required'   => 'El primer nombre es obligatorio.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
        ]);
    }

    public function setFeligres($value): void
    {
    $this->id_feligres = $value;
    }

    private function validateStepTwo(): void
    {
        $this->validate([
        'id_feligres' => ['required'],
    ], [
        'id_feligres.required' => 'Debe seleccionar un feligrés.',
    ]);
    }

    private function validateStepThree(): void
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
    {{-- Encabezado --}}
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900">Registrar Encargado</h1>
        <p class="mt-2 text-sm text-gray-600">Completa los 3 pasos para registrar un nuevo encargado.</p>
    </div>

    {{-- Stepper --}}
    <div class="flex items-center gap-2">
        @foreach ([1 => 'Persona', 2 => 'Feligrés', 3 => 'Firma'] as $num => $label)
            <div class="flex items-center gap-2 {{ $num < 3 ? 'flex-1' : '' }}">
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-semibold
                    {{ $step > $num ? 'bg-green-500 text-white' : ($step === $num ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500') }}">
                    {{ $step > $num ? '✓' : $num }}
                </span>
                <span class="text-sm {{ $step >= $num ? 'text-gray-900 font-medium' : 'text-gray-400' }}">{{ $label }}</span>
            </div>
            @if ($num < 3)
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
                    <x-input-label for="fecha_nacimiento" value="Fecha de Nacimiento" />
                    <x-text-input wire:model="fecha_nacimiento" id="fecha_nacimiento" class="mt-1 block w-full" type="date" />
                    <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="sexo" value="Sexo" />
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
                    <x-input-label for="dni" value="DNI / Documento" />
                    <x-text-input wire:model="dni" id="dni" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('dni')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="telefono" value="Teléfono" />
                    <x-text-input wire:model="telefono" id="telefono" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('telefono')" class="mt-1" />
                </div>
            </div>

            <div>
                <x-input-label for="email" value="Correo Electrónico" />
                <x-text-input wire:model="email" id="email" class="mt-1 block w-full" type="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="flex justify-end">
                <x-primary-button>Siguiente →</x-primary-button>
            </div>
        </form>
    @endif

    {{-- PASO 2: Seleccionar Feligrés --}}
    @if ($step === 2)
        <form wire:submit="nextStep" class="space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Seleccionar Feligrés</p>

            {{-- Resumen persona ingresada --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700">
                Persona: <span class="font-semibold">{{ $primer_nombre }} {{ $primer_apellido }}</span>
                @if($dni) · DNI: <span class="font-semibold">{{ $dni }}</span> @endif
            </div>
<div>
    <x-input-label for="id_feligres" value="Feligrés *" />
    <select x-on:change="$wire.setFeligres($event.target.value)" id="id_feligres"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        <option value="">-- Seleccionar feligrés --</option>
        @foreach ($this->feligresesDisponibles as $feligres)
            <option value="{{ $feligres->id_feligres }}">
                {{ $feligres->persona->primer_nombre }}
                {{ $feligres->persona->primer_apellido }}
                — DNI: {{ $feligres->persona->dni }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('id_feligres')" class="mt-1" />
</div>

            <div class="flex items-center justify-between">
                <x-secondary-button type="button" wire:click="previousStep">← Volver</x-secondary-button>
                <x-primary-button>Siguiente →</x-primary-button>
            </div>
        </form>
    @endif

    {{-- PASO 3: Firma / Imagen Encargado --}}
    @if ($step === 3)
        <form wire:submit="register" class="space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Firma del Encargado</p>

            {{-- Resumen --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700 space-y-1">
                <div>Persona: <span class="font-semibold">{{ $primer_nombre }} {{ $primer_apellido }}</span></div>
                <div>Feligrés seleccionado ID: <span class="font-semibold">#{{ $id_feligres }}</span></div>
            </div>

            <div>
                <x-input-label for="path_firma_principal" value="Imagen de Firma (opcional)" />
                <div class="mt-1">
                    <input
                        wire:model="path_firma_principal"
                        id="path_firma_principal"
                        type="file"
                        accept="image/*"
                        class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100"
                    />
                </div>
                <x-input-error :messages="$errors->get('path_firma_principal')" class="mt-1" />

                @if ($path_firma_principal)
                    <div class="mt-3">
                        <p class="text-xs text-gray-500 mb-1">Vista previa:</p>
                        <img src="{{ $path_firma_principal->temporaryUrl() }}"
                            alt="Vista previa firma"
                            class="h-32 w-auto rounded-md border border-gray-200 object-contain shadow-sm" />
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-between">
                <x-secondary-button type="button" wire:click="previousStep">← Volver</x-secondary-button>
                <x-primary-button>
                    <span wire:loading.remove wire:target="register">Registrar Encargado</span>
                    <span wire:loading wire:target="register">Guardando...</span>
                </x-primary-button>
            </div>
        </form>
    @endif

    {{-- Flash message --}}
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 p-3 text-sm text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
</div>