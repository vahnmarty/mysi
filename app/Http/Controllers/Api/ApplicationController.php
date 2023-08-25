<?php

namespace App\Http\Controllers\Api;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    public function index()
    {
        $data = Application::with('student','appStatus', 'payment')->get();
        return response()->json($data);
    }
}
