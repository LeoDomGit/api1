<?php

namespace App\Http\Controllers;

use App\Models\scheduleM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class scheduleControlller extends Controller
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
            'idTeacher'=>'required|numeric',
            'name'=>'required',
            'schedules'=>'required',
            'idCourse'=>'required|exists:courses,id',
            'duration'=>'required|numeric|min:0',

        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        scheduleM::create(['idTeacher','name','schedules','idCourse','duration']);
        $result=DB::Table('process_tbl')
        ->join('users','process_tbl.idTeacher','=','users.id')
        ->join('courses','process_tbl.idCourse','=','courses.id')
        ->select('process_tbl.*','courses.name as courseName','users.name as teacher')->get();
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(scheduleM $scheduleM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(scheduleM $scheduleM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, scheduleM $scheduleM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(scheduleM $scheduleM)
    {
        //
    }
}