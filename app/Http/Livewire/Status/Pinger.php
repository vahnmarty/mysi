<?php

namespace App\Http\Livewire\Status;

use Livewire\Component;
use Http;

class Pinger extends Component
{
    public $ping = 0;
    public function render()
    {
        $start=microtime(true);
        $fetch = Http::get(config('app.url')); 
        $latency=round((microtime(true)-$start)*1000);
        $this->ping = $latency;

        return view('livewire.status.pinger');
    }
}
