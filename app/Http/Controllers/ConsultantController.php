<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Consultant;

class ConsultantController extends Controller
{
    private $consultant;

    public function __construct()
    {
        $this->consultant = new Consultant();
    }

    public function login(Request $request)
    {
        $account=request()->get('account');
        $password=request()->get('password');
        if (!Consultant::where('email', $account)->exists()) {
            return response(['error' => 'error account'], 401);
        }

        $consultant = Consultant::where('email', $account)->first();
        if (!Hash::check($password, $consultant->password)) {
            return response(['error' => 'error password'], 401);
        }

        $data = [
            'account' => $consultant->email,
            'name' => $consultant->name,
            'nationality' => $consultant->nationality,
            'introduction' => $consultant->introduction,
        ];

        return response($data, 200);
    }
}
