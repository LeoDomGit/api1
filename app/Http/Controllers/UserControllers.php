<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRoleM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class UserControllers extends Controller
{
    public function editRole (Request $request,UserRoleM $UserRoleM)
    {
        $Validator = Validator::make($request->all(), [
            'id'=>'required|exists:role_tbl,id',
            'name'=>'required|unique:role_tbl,name'
        ],[
            'id.required'=>'Chưa nhận được mã loại tài khoản',
            'id.exists'=>'Loại tài khoản không tồn tại',
            'name.required'=>'Chưa nhận được loại tài khoản',
            'name.unique'=>'Loại tài khoản đã tồn tại'
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        UserRoleM::where('id',$request->id)->update(['name'=>$request->name,'updated_at'=>now()]);
        $result = DB::Table('role_tbl')->get();
        return response()->json(['check'=>true,'roles'=>$result]);
    }
        /**
     * Display a listing of the resource.
     */
    public function switchrole (Request $request,UserRoleM $UserRoleM)
    {
        $Validator = Validator::make($request->all(), [
            'id'=>'required|exists:role_tbl,id'
        ],[
            'id.required'=>'Chưa nhận được mã loại tài khoản',
            'id.exists'=>'Loại tài khoản không tồn tại',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $old = UserRoleM::where('id',$request->id)->value('status');
        if($old == 0){
            UserRoleM::where('id',$request->id)->update(['status'=>1,'updated_at'=>now()]);
        }else{
            UserRoleM::where('id',$request->id)->update(['status'=>0,'updated_at'=>now()]);

        }
        $result = DB::Table('role_tbl')->get();
        return response()->json(['check'=>true,'roles'=>$result]);
    }
        /**
     * Display a listing of the resource.
     */
    public function getRoles()
    {
        $result = DB::Table('role_tbl')->get();
        return response()->json($result);
    }
    /**
     * Display a listing of the resource.
     */
    public function createRole(Request $request,UserRoleM $UserRoleM)
    {
        $Validator = Validator::make($request->all(), [
            'name'=>'required|unique:role_tbl,name'
        ],[
            'name.required'=>'Chưa nhận được tên loại tài khoản',
            'name.unique'=>'Loại tài khoản đã tồn tại',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        UserRoleM::create(['name'=>$request->name,'created_at'=>now()]);
        $result = DB::Table('role_tbl')->get();
        return response()->json(['check'=>true,'roles'=>$result]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
