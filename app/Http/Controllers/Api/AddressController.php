<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    public function index()
    {
        return response()->json(Address::get());
    }

    public function sync(Request $request, Address $address)
    {
        $data = $request->only('sf_account_id', 'sf_residence_id', 'record_type_id');

        $address->update($data);

        return response()->json([
            'success' => true,
            'data' => $address
        ]);
    }
}
