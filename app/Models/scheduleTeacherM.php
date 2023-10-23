<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class scheduleTeacherM extends Model
{
    protected $table='class_schedule';
    protected $fillable = ['id','idTeacher','schedule','idcourse','created_at','updated_at'];
    use HasFactory;
}
