<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentClass;
use App\Models\Classes;
use App\Models\Student;
use Carbon\Carbon;


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
            $check_class = $this->student_class
                ->where('class_id', $data['class_id'])
                ->where('student_id', $data['student_id'])
                ->first();

            if (!empty($check_class)) {
                return response(['error' => 'Class already existed.'], 400);
            }

            $class = $this->classes
                ->where('id', $data['class_id'])
                ->first();

            $time_check = $this->student_class
                ->join('classes', 'classes.id', 'student_classes.class_id')
                ->where(function ($q) use ($class) {
                    $q->where('classes.start_date_time', '<=', $class['start_date_time'])
                        ->where('classes.end_date_time', '>=', $class['start_date_time']);
                })
                ->orwhere(function ($q) use ($class) {
                    $q->where('classes.start_date_time', '<=', $class['end_date_time'])
                        ->where('classes.end_date_time', '>=', $class['end_date_time']);
                })
                ->get();
            if (!empty($time_check)) {
                return response(['error' => 'This time has already had class.'], 400); 
            }

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

    public function show(Request $request, $student_id)
    {
        $classes = $this->student_class
            ->where('student_id', $student_id)
            ->get()
            ->pluck('class_id')
            ->toArray();

        if (!empty($classes)) {
            $result = $this->classes
                ->whereIn('id', $classes)
                ->get();
        } else {
            $result = null;
        }
        return response(['result' => $result], 200);
    }

    public function destroy(Request $request, $id)
    {
        $student_class = $this->student_class
            ->where('id', $id)
            ->first();
        if (empty($student_class)) {
            return response(['error' => 'The class does not exist.'], 400);
        }

        $class = $this->classes
            ->where('id', $student_class['class_id'])
            ->first();

        $now = Carbon::now()->toDateTimeString();

        if ($class['start_date_time'] <= $now) {
            return response(['error' => 'The class has already over time.'], 400);
        }

        $this->student_class
            ->where('id', $id)
            ->delete();

        return response(['result' => 'ok'], 200);
    }
}
