<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;

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
}
