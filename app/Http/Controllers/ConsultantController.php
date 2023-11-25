<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Consultant;
use App\Models\Classes;
use App\Models\StudentClass;
use App\Models\Student;


class ConsultantController extends Controller
{
    private $consultant;
    private $classes;
    private $student_class;
    private $student;

    public function __construct()
    {
        $this->consultant = new Consultant();
        $this->classes = new Classes();
        $this->student_class = new StudentClass();
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


    public function store(Request $request)
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

    public function show(Request $request, $class_id)
    {
        $student_ids = $this->student_class
            ->where('class_id', $class_id)
            ->get()
            ->pluck('student_id')
            ->toArray();
        if(!empty($student_ids)) {
            $students = $this->student
                ->whereIn('id', $student_ids)
                ->get()
                ->pluck('name');   
        }
        
        return response(['result' => $students], 200);
    }
}
