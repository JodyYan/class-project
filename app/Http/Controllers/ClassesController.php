<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\StudentClass;
use Carbon\Carbon;
use DB;

class ClassesController extends Controller
{
    private $classes;
    private $student_class;

    public function __construct()
    {
        $this->classes= new Classes();
        $this->student_class = new StudentClass();
    }

    public function store(Request $request)
    {
        try {
            if (!$request->has(['name', 'introduction', 'consultant_id', 'class_type_id','start_date_time', 'end_date_time'])) {
                return response(['error' => 'some columns empty'], 400);
            }
            $data = [
                'name' => request()->get('name'),
                'introduction' => request()->get('introduction'),
                'consultant_id' => request()->get('consultant_id'),
                'class_type_id' => request()->get('class_type_id'),
                'start_date_time' => request()->get('start_date_time'),
                'end_date_time' => request()->get('end_date_time'),
            ];
            Classes::create($data);
        } catch (\Exception $e) {
            return response(['result' => $e], 400);
        }
        
        return response(['result' => 'ok'], 201);
    }

    public function show(Request $request)
    {
        $data = [
            'name' => request()->get('name'),
            'introduction' => request()->get('introduction'),
            'consultant_id' => request()->get('consultant_id'),
            'class_type_id' => request()->get('class_type_id'),
        ];

        $start_date_time = request()->get('start_date_time');
        $end_date_time = request()->get('end_date_time');

        $result = $this->classes;
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $result = $result->where($key, $value);
            }
        }

        // 未指定區間只會撈尚未結束的課
        if (!empty($start_date_time)) {
            $result = $result->where('start_date_time', '>=', $start_date_time);
        } else {
            $now = Carbon::now()->toDateTimeString();
            if (empty($end_date_time)) {
                $result = $result->where('end_date_time', '>=', $now);
            } else {
                $result = $result->where('start_date_time', '>=', $now);
            }
        }
        if (!empty($end_date_time)) {
            $result = $result->where('end_date_time', '<=', $end_date_time)->get();
        } else {
            $result = $result->get();
        }
        
        return response(['result' => $result], 200);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = [
                'name' => request()->get('name'),
                'introduction' => request()->get('introduction'),
                'consultant_id' => request()->get('consultant_id'),
                'class_type_id' => request()->get('class_type_id'),
                'start_date_time' => request()->get('start_date_time'),
                'end_date_time' => request()->get('end_date_time'),
            ];
            $result = $this->classes->where('id', $id);

            foreach ($data as $key => $value) {
                if (empty($value)) {
                    unset($data[$key]);   
                }
            }
            $result = $result->update($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response(['result' => $e], 400);
        }
        
        return response(['result' => 'ok'], 200);
    }

    public function destroy($id)
    {
        $student_classes = $this->student_class->where('class_id', $id)->get()->toArray();
        if (!empty($student_classes)) {
            return response(['error' => 'The class still has student.'], 400);
        } 

        $this->classes->delete(['id' => $id]);

        return response(['result' => 'ok'], 200);
    }
}
