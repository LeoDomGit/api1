<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class processDetailM extends Model
{
    protected $table='proccess_detail';
    protected $fillable=['id','idProccess','idStudent','created_at','updated_at'];
    use HasFactory;
}
