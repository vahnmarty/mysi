<?php

namespace App\Http\Controllers\Api;

use App\Models\Parents;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ParentController extends Controller
{
    public $max = 10;

    /**
     * Display a listing of the resource.
     */
    public function summary(array $data)
    {
        return response()->json([
            'success' => true,
            'total' => count($data),
            'data' => $data
        ]);
    }

    public function index(Request $request)
    {
        $data = Parents::get();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salutation' => 'required',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'suffix' => 'nullable',
            'is_primary' => 'nullable',
            'preferred_first_name' => 'nullable',
            'mobile_phone' => 'required',
            'personal_email' => 'required|email',
            'employment_status' => 'nullable',
            'employer' => 'nullable',
            'job_title' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ], 
            400);
        }

        return response()->json([
            'success' => true,
            'data' => $validator->validated()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Parents $parent)
    {
        return response()->json([
            'success' => true,
            'data' => $parent
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parents $parent)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parents $parent)
    {
        $validator = Validator::make($request->all(), [
            'salutation' => 'required',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'suffix' => 'nullable',
            'is_primary' => 'nullable',
            'preferred_first_name' => 'nullable',
            'mobile_phone' => 'required',
            'personal_email' => 'required|email',
            'employment_status' => 'nullable',
            'employer' => 'nullable',
            'job_title' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ], 
            400);
        }

        return response()->json([
            'success' => true,
            'data' => $validator->validated()
        ]);
    }

    public function sync(Request $request, Parents $parent)
    {
        $data = $request->only('sf_account_id', 'sf_contact_id');

        $parent->update($data);

        return response()->json([
            'success' => true,
            'data' => $parent
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parents $parent)
    {
        $parent->delete();
    }
}
