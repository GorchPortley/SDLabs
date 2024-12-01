<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Design;

class DesignDescription extends Component
{
    public Design $design;
    public string $activeTab = 'description';

    public function mount(Design $design)
    {
        $this->design = $design;
    }

    public function setActiveTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function getAvailableTabsProperty()
    {
        $tabs = ['description' => true];

        if ($this->design->bill_of_materials) {
            $tabs['materials'] = true;
        }

        if ($this->design->forum_slug) {
            $tabs['forum'] = true;
        }

        return $tabs;
    }

    public function hasAccess(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->design->price < 0.01 ||
            $this->design->sales()->where('user_id', auth()->id())->exists() ||
            auth()->user()->hasRole('admin');
    }

    public function render()
    {
        return view('livewire.design-description');
    }
}
