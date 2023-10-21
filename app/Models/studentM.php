<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class studentM extends Model
{
    protected $table='students_tbl';
    protected $fillable=['id','name','email','phone','status','created_at','updated_at'];
    use HasFactory;
}
