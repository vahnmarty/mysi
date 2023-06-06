<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Auth;

class MyProfile extends Component
{
    public function render()
    {
        $user = Auth::user();
        return view('livewire.profile.my-profile', compact('user'));
    }
    
}
