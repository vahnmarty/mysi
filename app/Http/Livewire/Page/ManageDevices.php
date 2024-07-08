<?php

namespace App\Http\Livewire\Page;

use App\Models\Session;
use Livewire\Component;
use DB;

class ManageDevices extends Component
{
    public $sessions;

    public function render()
    {
        $this->sessions = Session::orderBy('last_activity', 'desc')->where('user_id', auth()->id())->get();


        return view('livewire.page.manage-devices');
    }

    public function mount()
    {
        
    }
}
