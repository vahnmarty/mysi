<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Validator;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $fetch = $request->fetch ?? 0;

        if($fetch){
            $data = Account::get();
        }else{
            $data = Account::has('users')->get();
        }

        return response()->json([
            'total' => count($data),
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'nullable',
            'salesforce_id' => 'required',
            'record_type_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ], 
            400);
        }
        $data = $validator->validated();

        $account = Account::firstOrCreate($data);

        return response()->json([
            'success' => true,
            'data' => $account
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Account $account)
    {
        return response()->json([
            'success' => true,
            'data' => $account
        ]);

        // Account{
        //     parents {}
        //     applications{
        //     app_status{
        //     }
        //     }
        //     student{}
        //     activities{}
        //     payment{}
        //     guardians{}
        //     children{}
        //     legacies{}
        // }

        $data = $account->load(
            'parents', 
                'applications.student', 
                'applications.activities', 
                'applications.payment', 
                'applications.appStatus', 
            'addresses', 
            'children.documents', 
            'legacies'
        );

        return response()->json([
            'account' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'nullable',
            'salesforce_id' => 'required',
            'record_type_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ], 
            400);
        }
        $data = $validator->validated();

        $account = $account->update($data);

        return response()->json([
            'success' => true,
            'data' => $account
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted'
        ]);
    }

    public function sync(Request $request, Account $account)
    {
        $data = $request->only('sf_account_id', 'record_type_id');

        $account->update($data);

        return response()->json([
            'success' => true,
            'data' => $account
        ]);
    }
}
