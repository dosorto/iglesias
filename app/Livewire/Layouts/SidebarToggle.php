<?php

namespace App\Livewire\Layouts;

use Livewire\Component;
use App\Models\TenantIglesia;
use App\Models\AppSetting;

class SidebarToggle extends Component
{
    public $isCollapsed = false;
    public ?string $logoUrl = null;
    public string $churchName = '';

    public function mount()
    {
        $this->isCollapsed = session('sidebar_collapsed', false);

        if (session('tenant.id_iglesia')) {
            $iglesia = TenantIglesia::current();
            $this->logoUrl = $iglesia?->logo_url;
            $this->churchName = $iglesia?->nombre ?: config('app.name', 'Holy App');

            return;
        }

        $setting = AppSetting::current();
        $this->logoUrl = $setting->company_logo_url;
        $this->churchName = $setting->company_name ?: 'NekoTech';
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
