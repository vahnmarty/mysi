<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class EditProfile extends Component implements HasForms
{
    use InteractsWithForms;
    
    public function render()
    {
        return view('livewire.profile.edit-profile');
    }
}
