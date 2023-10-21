<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillM extends Model
{
    protected $table='bill_tbl';
    protected $fillable=['id','idUser','idSchedule','status','created_at','updated_at'];
    use HasFactory;
}
