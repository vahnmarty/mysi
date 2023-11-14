<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Auth;

class BannerImpersonator extends Component
{
    public function render()
    {
        return view('livewire.banner-impersonator');
    }

    public function leave()
    {
        Auth::user()->leaveImpersonation();

        return redirect('admin/force-login');
    }
}
