<?php

namespace App\Livewire\Encargado;

use App\Models\Encargado;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class EncargadoEdit extends Component
{
    use WithFileUploads;

    public Encargado $encargado;

    public $firma;

    public string $telefono = '';

    public string $email = '';

    public string $fecha_nacimiento = '';

    public string $sexo = '';

    public function mount(Encargado $encargado): void
    {
        $this->encargado = $encargado->load('feligres.persona');

        $persona = $this->encargado->feligres?->persona;

        if ($persona) {
            $this->telefono = (string) ($persona->telefono ?? '');
            $this->email = (string) ($persona->email ?? '');
            $this->fecha_nacimiento = $persona->fecha_nacimiento?->format('Y-m-d') ?? '';
            $this->sexo = (string) ($persona->sexo ?? '');
        }
    }

    public function update(): void
    {
        $this->validate([
            'telefono' => ['required', 'string', 'regex:/^[0-9\+\-\s]+$/', 'min:8', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'sexo' => ['nullable', 'in:M,F'],
            'firma' => ['nullable', 'image', 'max:2048'],
        ], [
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'telefono.min' => 'El teléfono debe tener al menos 8 dígitos.',
            'sexo.in' => 'Selecciona Masculino o Femenino.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        ]);

        $persona = $this->encargado->feligres?->persona;

        if (! $persona) {
            $this->addError('telefono', 'No se encontró la persona asociada al encargado.');

            return;
        }

        DB::transaction(function () use ($persona): void {
            $persona->update([
                'telefono' => $this->telefono,
                'email' => $this->email ?: null,
                'fecha_nacimiento' => $this->fecha_nacimiento ?: null,
                'sexo' => $this->sexo ?: null,
            ]);

            if ($this->firma) {
                $this->encargado->update([
                    'path_firma_principal' => $this->firma->store('firmas-encargado', 'public'),
                ]);
            }
        });

        session()->flash('success', 'Encargado actualizado correctamente.');
        $this->redirect(route('encargado.show', $this->encargado), navigate: false);
    }

    public function render()
    {
        return view('livewire.encargado.encargado-edit');
    }
}
