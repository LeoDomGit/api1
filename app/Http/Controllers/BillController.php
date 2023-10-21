<?php

namespace App\Http\Controllers;

use App\Models\BillM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserRoleM;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\BillMail;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'phone'=>'required|max:10',
            'name'=>'required',
            'idSchedule'=>'required|exists:class_schedule,id'
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $findUser=User::where('email',$request->email)->first();
        if(!$findUser){
            $password=11111;
            $idRole=UserRoleM::where('name','=','Student')->value('id');
            $idUser=User::insertGetId([
                'name'=>$request->name,
                'email'=>$request->email,
                'idRole'=>$idRole,
                'phone'=>$request->phone,
                'password'=>Hash::make($password),
            ]);
            BillM::create(['idUser'=>$idUser,'idSchedule'=>$request->idSchedule,'created_at'=>now()]);
            $result = DB::Table('class_schedule')
            ->join('courses','class_schedule.idcourse','=','courses.id')
            ->where('class_schedule.id','=',$request->idSchedule)
            ->select('courses.name as name','courses.price as price','courses.discount as discount')
            ->get();
            $course = new stdClass;
            $course->name = $result[0]['name'];
            $course->price = $result[0]['price'] * (100-$result[0]['discount'])/100;
            $mailData=[
                'name'=>$request->name,
                'course'=>$course,
            ];
            Mail::to($request->email)->send(new BillMail($mailData));
        }else{
            $idUser= $findUser->id;
            BillM::create(['idUser'=>$idUser,'idSchedule'=>$request->idSchedule,'created_at'=>now()]);
            $result = DB::Table('class_schedule')
            ->join('courses','class_schedule.idcourse','=','courses.id')
            ->where('class_schedule.id','=',$request->idSchedule)
            ->select('courses.name as name','courses.price as price','courses.discount as discount')
            ->get();
            $course = (object) [
                'name'=>'',
                'price'=>0,
            ];
            $course->name = $result[0]->name;
            $course->price =$result[0]->price * (100-$result[0]->discount)/100;
            $mailData=[
                'name'=>$request->name,
                'course'=>$course,
            ];
            Mail::to($request->email)->send(new BillMail($mailData));
            return response()->json(['check'=>true]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BillM $billM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BillM $billM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BillM $billM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillM $billM)
    {
        //
    }
}
