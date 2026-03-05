<?php

namespace App\Livewire\PrimeraComunion;

use App\Models\PrimeraComunion;
use Livewire\Component;

class PrimeraComunionShow extends Component
{
    public PrimeraComunion $primeraComunion;

    public function mount(PrimeraComunion $primeraComunion): void
    {
        $this->primeraComunion = $primeraComunion->load([
            'iglesia',
            'feligres.persona',
            'catequista.persona',
            'ministro.persona',
            'parroco.persona',
        ]);
    }

    public function render()
    {
        return view('livewire.primera-comunion.primera-comunion-show');
    }
}