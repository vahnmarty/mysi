<?php

namespace App\Exports;

use App\Models\Application;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class UnpaidApplications implements FromView
{

    public function view(): View
    {
        return view('exports.applications', [
            'applications' => Application::get()
        ]);
    }

}
