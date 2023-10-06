<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Legacy;
use Illuminate\Http\Request;

class LegacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Legacy::get());
    }

    public function sync(Request $request, Legacy $legacy)
    {
        $data = $request->only('sf_legacy_id', 'sf_application_id');

        $legacy->update($data);

        return response()->json([
            'success' => true,
            'data' => $legacy
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Legacy $legacy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Legacy $legacy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Legacy $legacy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Legacy $legacy)
    {
        //
    }
}
