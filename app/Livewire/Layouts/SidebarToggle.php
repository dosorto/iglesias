<?php

namespace App\Livewire\Layouts;

use Livewire\Component;
use App\Models\Iglesias;

class SidebarToggle extends Component
{
    public $isCollapsed = false;
    public ?string $logoUrl = null;

    public function mount()
    {
        $this->isCollapsed = session('sidebar_collapsed', false);
        $iglesia = Iglesias::currentFromSession();
        $this->logoUrl = $iglesia?->logo_url;
    }

    public function toggleSidebar()
    {
        $this->isCollapsed = !$this->isCollapsed;
        session(['sidebar_collapsed' => $this->isCollapsed]);
    }

    public function render()
    {
        return view('livewire.layouts.sidebar-toggle');
    }
}
