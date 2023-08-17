<?php

namespace App\Http\Controllers\Api;

use App\Models\Child;
use App\Models\Legacy;
use App\Models\School;
use App\Models\Account;
use App\Models\Address;
use App\Models\Parents;
use App\Models\Payment;
use App\Models\Activity;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ApplicationStatus;
use App\Http\Controllers\Controller;

class SalesforceController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $data['accounts'] = Account::get();
        $data['activities'] = Activity::get();
        $data['addresses'] = Address::get();
        $data['applications'] = Application::get();
        $data['appication_status'] = ApplicationStatus::get();
        $data['children'] = Child::get();
        $data['legacies'] = Legacy::get();
        $data['parents'] = Parents::get();
        $data['payments'] = Payment::get();
        $data['schools'] = School::get();

        return response()->json($data);
    }
}
