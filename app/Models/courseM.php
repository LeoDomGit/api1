<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courseM extends Model
{
    protected $table='courses';
    protected $fillable=['id','name','Grade','status','image','duration','summary','price','discount','idCourseCate','detail','created_at','updated_at'];
    use HasFactory;
}
