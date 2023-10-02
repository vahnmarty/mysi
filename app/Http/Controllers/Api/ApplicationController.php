<?php

namespace App\Http\Controllers\Api;

use App\Models\School;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with('student','appStatus', 'payment', 'legacies');

        if($request->file_learning_documentation){
            $query = $query->whereNotNull('file_learning_documentation');
        }

        $data = $query->get();
        
        foreach($data as $i => $app)
        {
            if(!empty($app['student']['current_school'])){
                $data[$i]['current_school'] = School::where('name', $app['student']['current_school'])
                            ->first()
                            ->toArray();
            }
            
        }

        return response()->json($data);
    }
}
