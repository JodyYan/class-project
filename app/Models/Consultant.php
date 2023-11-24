<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Consultant extends Model
{
    protected $table = 'consultants';
    protected $guarded = [];
    protected $hidden = ['password'];

    use HasFactory;
}
