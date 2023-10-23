<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRoleM;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Mail\UserMail;
use Illuminate\Support\Facades\Hash;
use App\Mail\ContactMail;
class UserControllers extends Controller
{


    public function sendContact(Request $request){
        $Validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'phone'=>'required|max:10',
            'subject'=>'required',
            'message'=>'required',
            'name'=>'required',
        ],[
            'name.required'=>'Thiếu tên người liên hệ',
            'email.required'=>'Thiếu email liên hệ',
            'phone.required'=>'Thiếu số điện thoại liên hệ',
            'subject.required'=>'Thiếu tiêu đề cần yêu cầu',
            'message.required'=>'Thiếu nội dung yêu cầu',
            'phone.max'=>'Số điện thoại liên hệ không hợp lệ' ,
            'email.email'=>'Email liên hệ không hợp lệ',
            

        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $mailData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'content' => $request->email,
        ];
        $users = DB::Table('users')->join('role_tbl','users.idRole','=','role_tbl.id')
        ->where('role_tbl.name','admin')->first();
        $email=$users->email;
        Mail::to($email)->send(new ContactMail($mailData));
        return response()->json(['check'=>true]);
    }
    // =================================================
    public function getTeacher(){
        $result= DB::Table('users')->join('role_tbl','users.idRole','=','role_tbl.id')
        ->where('role_tbl.name','=','Teacher')->select('users.*')
        ->get();
        return response()->json($result);
    }
    // ==================================================
    public function checkLoginAdmin (Request $request,UserRoleM $UserRoleM, User $User){
        $Validator = Validator::make($request->all(), [
            'email'=>'required|email|exists:users,email',

        ],[
            'email.required'=>'Thiếu email tài khoản',
            'email.email'=>'Email tài khoản không hợp lệ',
            'email.exists'=>'Email tài khoản không tồn tại',

        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $users = DB::Table('users')
        ->join('role_tbl','users.idRole','=','role_tbl.id')
        ->where('role_tbl.name','=','admin')->where('users.email',$request->email)->get();
        if(count($users)>0){
            return response()->json(['check'=>true]);
        }
    }
    
    //=====================================================

    public function switchUser(Request $request,UserRoleM $UserRoleM, User $User){
        $Validator = Validator::make($request->all(), [
            'id'=>'required|numeric|exists:users,id',

        ],[
            'id.required'=>'Chưa nhận được mã tài khoản',
            'id.exists'=>'Mã tài khoản không tồn tại',
            'id.numeric'=>'Mã tài khoản không hợp lệ',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $old= User::where('id',$request->id)->value('status');
        if($old==0){
            User::where('id',$request->id)->update(['status'=>1,'updated_at'=>now()]);
        }else{
            User::where('id',$request->id)->update(['status'=>0,'updated_at'=>now()]);
        }
        $users= DB::Table('users')
        ->join('role_tbl','users.idRole','=','role_tbl.id')
        ->select('users.id as id','users.name as name','users.email as email','users.status as status','users.idRole as idRole','users.status as status','role_tbl.name as rolename')
        ->get();
        return response()->json($users);
    }

    // ==================================================

    public function checkLogin1 (Request $request,UserRoleM $UserRoleM, User $User){
        $Validator = Validator::make($request->all(), [
            'email'=>'required|email|exists:users,email',
            'password'=>'required',

        ],[
            'email.required'=>'Chưa nhận được email tài khoản',
            'idRole.required'=>'Chưa nhận được loại tài khoản',
            'idRole.exists'=>'Loại tài khoản không tồn tại',
            'email.email'=>'Email tài khoản không hợp lệ',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
    }
    // ==================================================
    public function checkLogin (Request $request,UserRoleM $UserRoleM, User $User)
    {
        $Validator = Validator::make($request->all(), [
            'email'=>'required|email|exists:users,email',
            'password'=>'required',

        ],[
            'email.required'=>'Chưa nhận được email tài khoản',
            'idRole.required'=>'Chưa nhận được loại tài khoản',
            'idRole.exists'=>'Loại tài khoản không tồn tại',
            'email.email'=>'Email tài khoản không hợp lệ',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        if(Auth::attempt(['email' => $request->email,'status'=>1, 'password' => $request->password, 'status' => 1],true)){
          $token=Auth::user()->remember_token;
          return response()->json(['check'=>true,'token'=>$token]);
        }else{
          return response()->json(['check'=>false,'msg'=>'Sai tên đăng nhập hoặc mật khẩu']);

        }
    }

    // ===================================================
    public function getUsers(){
        $users= DB::Table('users')
        ->join('role_tbl','users.idRole','=','role_tbl.id')
        ->select('users.id as id','users.name as name','users.email as email','users.status as status','users.idRole as idRole','users.status as status','role_tbl.name as rolename')
        ->get();
        return response()->json($users);
    }
       // ===================================================

    public function editUser(Request $request,UserRoleM $UserRoleM, User $User)
    {
        $Validator = Validator::make($request->all(), [
            'id'=>'required|exists:users,id',
            'username'=>'required',
            'email'=>'required|email',
            'idRole'=>'required|exists:role_tbl,id',

        ],[
            'id.required'=>'Chưa nhận được mã tài khoản',
            'id.exists'=>'Mã tài khoản không tồn tại',
            'username.required'=>'Chưa nhận được tên tài khoản',
            'email.required'=>'Chưa nhận được email tài khoản',
            'idRole.required'=>'Chưa nhận được loại tài khoản',
            'idRole.exists'=>'Loại tài khoản không tồn tại',
            'email.email'=>'Email tài khoản không hợp lệ',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        User::where('id',$request->id)->update(['name'=>$request->username,'email'=>$request->email,'idRole'=>$request->idRole,'created_at'=>now()]);
        $users= DB::Table('users')
        ->join('role_tbl','users.idRole','=','role_tbl.id')
        ->select('users.id as id','users.name as name','users.email as email','users.status as status','users.idRole as idRole','users.status as status','role_tbl.name as rolename')
        ->get();
        return response()->json(['check'=>true,'users'=>$users]);
    }
    // ===================================================

    public function createUser(Request $request,UserRoleM $UserRoleM, User $User)
    {
        $Validator = Validator::make($request->all(), [
            'username'=>'required',
            'email'=>'required|email|unique:users,email',
            'idRole'=>'required|exists:role_tbl,id',

        ],[
            'username.required'=>'Chưa nhận được tên tài khoản',
            'email.required'=>'Chưa nhận được email tài khoản',
            'idRole.required'=>'Chưa nhận được loại tài khoản',
            'idRole.exists'=>'Loại tài khoản không tồn tại',
            'email.email'=>'Email tài khoản không hợp lệ',
            'email.unique'=>'Email tài khoản bị trùng lặp',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $random_int= 111111; 
        $password = Hash::make($random_int);
        User::create(['name'=>$request->username,'password'=>$password,'email'=>$request->email,'idRole'=>$request->idRole,'created_at'=>now()]);
        $mailData = [
            'title' => 'Mail thông báo thông tin tài khoản ',
            'email' => $request->email,
            'name' => $request->username,
            'password' => $random_int,
        ];
        Mail::to($request->email)->send(new UserMail($mailData));
        $users= DB::Table('users')
        ->join('role_tbl','users.idRole','=','role_tbl.id')
        ->select('users.id as id','users.name as name','users.status as status','users.email as email','users.idRole as idRole','users.status as status','role_tbl.name as rolename')
        ->get();
        return response()->json(['check'=>true,'users'=>$users]);
    }
    /**
     * Display a listing of the resource.
    */
    public function deleteRole (Request $request,UserRoleM $UserRoleM, User $User)
    {
        $Validator = Validator::make($request->all(), [
            'id'=>'required|exists:role_tbl,id',
        ],[
            'id.required'=>'Chưa nhận được mã loại tài khoản',
            'id.exists'=>'Loại tài khoản không tồn tại',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $count = User::where('idRole',$request->id)->count('id');
        if($count!=0){
            return response()->json(['check'=>false,'msg'=>'Còn tồn tại tài khoản trong loại']);
        }
        UserRoleM::where('id',$request->id)->delete();
        $result = DB::Table('role_tbl')->get();
        return response()->json(['check'=>true,'roles'=>$result]);
    }
    /**
     * Display a listing of the resource.
    */
    
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
