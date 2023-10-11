<?php

namespace App\Http\Controllers\Api;

use App\Models\Child;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function index()
    {
        return response()->json(Child::student()->get());
    }

    public function sync(Request $request, Child $student)
    {
        $data = $request->only('sf_account_id', 'sf_contact_id', 'record_type_id');

        $student->update($data);

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }
}
