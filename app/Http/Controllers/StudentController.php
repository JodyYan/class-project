<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    private $student;

    public function __construct()
    {
        $this->student = new Student();
    }

    public function login(Request $request)
    {
        try {
            if (!$request->has(['account', 'password'])) {
                return response(['error' => 'some columns empty'], 400);
            }
            $account=request()->get('account');
            $password=request()->get('password');
            if (!$this->student->where('email', $account)->exists()) {
                return response(['error' => 'error account'], 401);
            }

            $student = $this->student->where('email', $account)->first();
            if (!Hash::check($password, $student->password)) {
                return response(['error' => 'error password'], 401);
            }

            $data = [
                'account' => $student->email,
                'name' => $student->name,
            ];
        } catch (\Exception $e) {
            return response(['result' => $e], 400);
        }
        
        return response(['result' => $data], 200);
    }

}
