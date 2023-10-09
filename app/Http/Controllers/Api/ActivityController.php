<?php

namespace App\Http\Controllers\Api;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    public function index()
    {
        return response()->json(Activity::get());
    }

    public function sync(Request $request, Activity $activity)
    {
        $data = $request->only('sf_activity_id', 'sf_application_id', 'record_type_id');

        $activity->update($data);

        return response()->json([
            'success' => true,
            'data' => $activity
        ]);
    }
}
