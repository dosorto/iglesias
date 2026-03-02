<?php

namespace App\Livewire\Bautismo;

use Livewire\Component;
use App\Models\Bautismo;

class BautismoIndex extends Component
{
    public function render()
    {
        return view('livewire.bautismo.bautismo-index', [
            'bautismos' => Bautismo::with(['iglesia', 'bautizado.persona'])
                ->latest()
                ->paginate(20),
        ]);
    }
}
