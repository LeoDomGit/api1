<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courseCateM extends Model
{
    protected $table='course_cates';
    protected $fillable=['id','name','idEdu','status','created_at','updated_at'];
    use HasFactory;
}
