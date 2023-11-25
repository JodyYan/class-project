<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Student extends Model
{
    protected $table = 'students';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    
    use HasFactory;

}
