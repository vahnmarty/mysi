<?php

namespace App\Http\Controllers\Api;

use App\Models\Child;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChildController extends Controller
{
    public function index()
    {
        return response()->json(Child::get());
    }
}
