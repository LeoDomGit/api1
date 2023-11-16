<?php

namespace App\Http\Controllers;

use App\Models\EduM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EduController extends Controller
{
/**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = DB::Table('edu_tbl')->paginate(10);
        return response()->json($result);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function getEducations()
    {
        $result = EduM::where('status',1)->get();
        return response()->json($result);
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
    public function store(Request $request,EduM $EduM)
    {
        $Validator = Validator::make($request->all(), [
            'name'=>'required|unique:edu_tbl,name',

        ],[
            'name.required'=>'Chưa nhận được loại hình giáo dục',
            'name.unique'=>'Loại hình giáo dục đã tồn tại',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        EduM::create(['name'=>$request->name,'created_at'=>now()]);
        $result = DB::Table('edu_tbl')->get();
        return response()->json(['check'=>true,'edu'=>$result]);
    }

    /**
     * Display the specified resource.
     */
    public function show(EduM $eduM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,EduM $EduM)
    {
        $Validator = Validator::make($request->all(), [
            'name'=>'required|unique:edu_tbl,name',
            'id'=>'required|exists:edu_tbl,id'
        ],[
            'id.required'=>'Mã giáo dục chưa nhận được',
            'id.exists'=>'Mã giáo dục không tồn tại',
            'name.required'=>'Chưa nhận được loại hình giáo dục',
            'name.unique'=>'Loại hình giáo dục đã tồn tại',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        EduM::where('id',$request->id)->update(['name'=>$request->name,'updated_at'=>now()]);
        $result = DB::Table('edu_tbl')->get();
        return response()->json(['check'=>true,'edu'=>$result]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EduM $eduM)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function switchEdu(Request $request, EduM $eduM)
    {
        $Validator = Validator::make($request->all(), [
           
            'id'=>'required|exists:edu_tbl,id'
        ],[
            'id.required'=>'Mã giáo dục chưa nhận được',
            'id.exists'=>'Mã giáo dục không tồn tại',
         
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $old = EduM::where('id',$request->id)->value('status');
        if($old==0){
            EduM::where('id',$request->id)->update(['status'=>1,'updated_at'=>now()]);
        }else{
            EduM::where('id',$request->id)->update(['status'=>0,'updated_at'=>now()]);
        }
        $result = DB::Table('edu_tbl')->get();
        return response()->json(['check'=>true,'edu'=>$result]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, EduM $eduM)
    {
        $Validator = Validator::make($request->all(), [
           
            'id'=>'required|exists:edu_tbl,id'
        ],[
            'id.required'=>'Mã giáo dục chưa nhận được',
            'id.exists'=>'Mã giáo dục không tồn tại',
         
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $check = DB::Table('cate_tbl')->where('idEdu',$request->id)->count('id');
        if($check!=0){
            return response()->json(['check'=>false,'msg'=>"Còn tồn tại loại lớp trong lĩnh vực"]);
        }
        EduM::where('id',$request->id)->delete();
        $result = DB::Table('edu_tbl')->get();
        return response()->json(['check'=>true,'edu'=>$result]);
    }
}
