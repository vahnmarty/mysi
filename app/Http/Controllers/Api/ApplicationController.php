<?php

namespace App\Http\Controllers\Api;

use App\Models\School;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    public function index()
    {
        $data = Application::with('student','appStatus', 'payment')->get();
        
        foreach($data as $i => $app)
        {
            $data[$i]['current_school'] = School::where('name', $app['student']['current_school'])->first()->toArray();
        }

        return response()->json($data);
    }
}
