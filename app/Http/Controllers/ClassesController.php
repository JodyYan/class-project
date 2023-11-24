<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use Carbon\Carbon;

class ClassesController extends Controller
{
    private $classes;

    public function __construct()
    {
        $this->classes= new Classes();
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
        
        return response(['result' => 'ok'], 200);
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

        // 只會撈尚未結束的課
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
}
