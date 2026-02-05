<?php

namespace App\Livewire\Layouts;

use Livewire\Component;

class SidebarToggle extends Component
{
    public $isCollapsed = false;

    public function mount()
    {
        $this->isCollapsed = session('sidebar_collapsed', false);
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
