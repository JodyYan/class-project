<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentClass;
use App\Models\Classes;
use App\Models\Student;

class StudentClassController extends Controller
{
    private $student_class;
    private $classes;
    private $student;

    public function __construct()
    {
        $this->student_class = new StudentClass();
        $this->classes= new Classes();
        $this->student = new Student();
    }

    public function store(Request $request)
    {
        try {
            if (!$request->has(['student_id', 'class_id'])) {
                return response(['error' => 'some columns empty'], 400);
            }
            $data = [
                'class_id' => request()->get('class_id'),
                'student_id' => request()->get('student_id'),
            ];
            if (empty($this->classes->where('id', $data['class_id'])->get()->toArray())) {
                return response(['error' => 'Class does not exist.'], 400);   
            }
            if (empty($this->student->where('id', $data['student_id'])->get()->toArray())) {
                return response(['error' => 'Student does not exist.'], 400);   
            }

            $this->student_class->create($data);
        } catch (\Exception $e) {
            return response(['result' => $e], 400);
        }
        
        return response(['result' => 'ok'], 200);
    }
}
