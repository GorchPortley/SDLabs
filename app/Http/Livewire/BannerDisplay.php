<?php

namespace App\Http\Livewire;

use App\Models\Banner;
use Livewire\Component;

class BannerDisplay extends Component
{
    public $location;
    public $banners;

    public function mount($location)
    {
        $this->location = $location;
        $this->loadBanners();
    }

    public function loadBanners()
    {
        $this->banners = Banner::activeForLocation($this->location)->get();
    }

    public function render()
    {
        return view('livewire.banner-display', [
            'banners' => $this->banners
        ]);
    }
}
