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

    public function sync(Request $request, Child $child)
    {
        $data = $request->only('sf_account_id', 'sf_contact_id');

        $child->update($data);

        return response()->json([
            'success' => true,
            'data' => $child
        ]);
    }
}
