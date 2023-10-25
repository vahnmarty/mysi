<?php

namespace App\Http\Controllers\Api;

use App\Models\Child;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChildController extends Controller
{
    public function index(Request $request)
    {
        $max = 100;

        if($request->n){
            $max = $request->n;
        }

        $data = Child::paginate($max);

        return response()->json($data);
    }

    public function sync(Request $request, Child $child)
    {
        $data = $request->only('sf_account_id', 'sf_contact_id', 'record_type_id');

        $child->update($data);

        return response()->json([
            'success' => true,
            'data' => $child
        ]);
    }

    public function show(Child $child)
    {
        return response()->json([
            'success' => true,
            'data' => $child
        ]);
    }
}
