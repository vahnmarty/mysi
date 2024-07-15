<?php

namespace App\Http\Livewire\Auth;

use App\Models\Session;
use Livewire\Component;

class ActiveDevices extends Component
{
    public $devices = [];
    
    public function render()
    {
        return view('livewire.auth.active-devices');
    }

    public function mount()
    {
        $this->devices = Session::where('user_id', auth()->id())->count();
    }
}
