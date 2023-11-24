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
        try {
            if (!$request->has(['account', 'password'])) {
                return response(['error' => 'some columns empty'], 400);
            }
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
        } catch (\Exception $e) {
            return response(['result' => $e], 400);
        }
        
        return response(['result' => $data], 200);
    }


    public function createConsultant(Request $request)
    {
        try {
            if (!$request->has(['name', 'email', 'nationality', 'introduction', 'password', 'sex'])) {
                return response(['error' => 'some columns empty'], 400);
            }
            $data = [
                'email' => request()->get('email'),
                'name' => request()->get('name'),
                'nationality' => request()->get('nationality'),
                'introduction' => request()->get('introduction'),
                'password' => Hash::make(request()->get('password')),
                'sex' => request()->get('sex'),
            ];
            Consultant::create($data);
        } catch (\Exception $e) {
            return response(['result' => $e], 400);
        }
        
        return response(['result' => 'ok'], 200);
    }

    public function index(Request $request)
    {
        $data = Consultant::get();
        return response(['result' => $data], 200);
    }
}
