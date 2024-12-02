<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Design;

class DesignDescription extends Component
{
    public Design $design;
    public string $activeTab = '';

    public function mount(Design $design)
    {
        $this->design = $design;
        if ($this->hasAccess) {
            $this->activeTab = 'tab2';
        } else {
            $this->activeTab = 'tab1';
        }
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

    public function getHasAccessProperty()
    {
        // Not logged in
        if (!auth()->check()) {
            return false;
        }

        // Free design
        if ($this->design->price < 0.01) {
            return true;
        }

        // User is the designer
        if ($this->design->designer->id === auth()->id()) {
            return true;
        }

        // User is admin
        if (auth()->user()->hasRole('admin')) {
            return true;
        }

        // User has purchased the design
        if ($this->design->sales->contains(function($sale) {
            return $sale->user_id === auth()->id();
        })) {
            return true;
        }

        return false;
    }

    public function render()
    {
        return view('livewire.design-description');
    }
}
