<?php

namespace App\Livewire\Encargado;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Encargado;

class EncargadoEdit extends Component
{
    use WithFileUploads;

    public Encargado $encargado;
    public $firma;

    public function mount(Encargado $encargado): void
    {
        $this->encargado = $encargado;
    }

    public function update(): void
    {
        $this->validate([
            'firma' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [];

        if ($this->firma) {
            $data['path_firma_principal'] = $this->firma->store('firmas-encargado', 'public');
        }

        if (!empty($data)) {
            $this->encargado->update($data);
        }

        session()->flash('success', 'Encargado actualizado correctamente.');
        $this->redirect(route('encargado.show', $this->encargado), navigate: false);
    }

    public function render()
    {
        return view('livewire.encargado.encargado-edit');
    }
}
