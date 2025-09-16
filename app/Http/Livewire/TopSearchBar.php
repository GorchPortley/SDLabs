<?php
namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\Driver;
use App\Models\User;
use App\Models\Design;

class TopSearchBar extends Component
{
    public $search = "";
    
    public function render()
    {
        $users = collect([]);
        $designs = collect([]);
        $drivers = collect([]);
        
        if(strlen($this->search) >= 2) {
            $users = User::search($this->search)->get();
            $designs = Design::search($this->search)->where('active', 1)->get();
            $drivers = Driver::search($this->search)->where('active', 1)->get();
        } else{
            $users = [];
            $designs = [];
            $drivers = [];
        }
        
        return view('livewire.top-search-bar', [
            'users' => $users,
            'designs' => $designs,
            'drivers' => $drivers
        ]);
    }
}