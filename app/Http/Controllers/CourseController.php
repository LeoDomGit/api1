<?php

namespace App\Http\Controllers;

use App\Models\courseCateM;
use App\Models\courseM;
use App\Models\scheduleM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
class CourseController extends Controller
{

    public function getCourseClass(scheduleM $scheduleM,courseCateM $courseCateM,Request $request){
        $Validator = Validator::make($request->all(), [
            'id'=>'required|exists:courses,idCourseCate',
            'name'=>'required|exists:courses,Grade',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $result= courseM::where('idCourseCate','=',$request->id)->where('Grade','Like',$request->name)->paginate(6);
        if(count($result)==0){
            return response()->json(['check'=>false]);
        }
        return response()->json(['check'=>true,'course'=>$result]);
    }
//====================================================
    public function getClass($id){
        $result= DB::Table('courses')->where('idCourseCate',$id)->distinct()
        ->select('Grade')
        ->get();
        return response()->json($result);
    }
// =====================================
    public function getCourseCate($id){
        $result = DB::Table('course_cates')->where('idEdu',$id)->select('id','name')->get();
        return response()->json($result);
    }
    //===================================
    public function getSingleCourses($id){
        $result= DB::Table('courses')
        ->join('course_cates','courses.idCourseCate','=','course_cates.id')
        ->join('edu_tbl','course_cates.idEdu','=','edu_tbl.id')
        ->where('courses.id',$id)
        ->select('courses.*','course_cates.name as catename','edu_tbl.name as eduname')
        ->take(1)->get();
        return response()->json($result);
    }
    // ============================================
    public function getCurrentCourses()
    {
        $courses= DB::Table('courses')
        ->join('course_cates','courses.idCourseCate','=','course_cates.id')
        ->join('edu_tbl','course_cates.idEdu','=','edu_tbl.id')
        ->orderBy('created_at','desc')
        ->select('courses.*','course_cates.name as catename','edu_tbl.name as eduname')
        ->take(4)->get();
        return response()->json($courses);
    }
    //==============================================
    public function duplicateCourse($id){
        $courses = courseM::where('id',$id)->first();
        courseM::create(['name'=>$courses->name,'summary'=>$courses->summary,'image'=>$courses->image,'Grade'=>$courses->Grade,'price'=>$courses->price,'discount'=>$courses->discount,'idCourseCate'=>$courses->idCourseCate,'duration'=>$courses->duration,'detail'=>$courses->detail,'created_at'=>now()]);
        $result = DB::Table('courses')->where('idCourseCate',$courses->idCourseCate)->paginate(5);
        return response()->json($result);
    }
    // ----------------------------------------------
    public function coursecates($id)
    {
        $result = DB::Table('course_cates')
        ->where('idEdu',$id)
        ->where('status',1)
        ->select('id','name')->get();
        return response()->json($result);
    }
        // ====================================
    public function getCoursesUser(){
        $result = DB::Table('courses')
        ->join('course_cates','courses.idCourseCate','=','course_cates.id')
        ->join('edu_tbl','course_cates.idEdu','=','edu_tbl.id')
        ->where('courses.status',1)
        ->select('courses.*','edu_tbl.name as edu','course_cates.name as catename')
        ->get();
        return response()->json($result);
    }
    // ====================================


    public function allSchedule(){
        $result = DB::Table('class_schedule')
        ->join('courses','class_schedule.idcourse','=','courses.id')
        ->join('course_cates','courses.idCourseCate','=','course_cates.id')
        ->join('edu_tbl','course_cates.idEdu','=','edu_tbl.id')
        ->join('users','class_schedule.idTeacher','=','users.id')
        ->select("class_schedule.*",'courses.name as name',"course_cates.name as catename", "edu_tbl.name as eduName", "users.name as username")
        ->paginate(5);
        return response()->json($result);

    
    }
// =========================================================
    public function getSchedule (){
        $result = DB::Table('class_schedule')
        ->join('courses','class_schedule.idcourse','=','courses.id')->join('course_cates','courses.idCourseCate','=','course_cates.id')
        ->join('users','class_schedule.idTeacher','=','users.id')
        ->select('class_schedule.*','courses.name as coursename','course_cates.name as catename','users.name as username')
        ->paginate(5);
        return response()->json($result);
    }
// ==========================================================
    public function addSchedule (scheduleM $scheduleM,courseCateM $courseCateM,Request $request){
        $Validator = Validator::make($request->all(), [
            'teacher'=>'required|exists:users,id',
            'course'=>'required|exists:courses,id',
            'times'=>'required',

        ],[
            'teacher.required'=>'Thiếu mã giáo viên',
            'course.required'=>'Thiếu mã khóa học',
            'times.required'=>'Thiếu lịch giảng dạy',
            'teacher.exists'=>'Mã giáo viên không tồn tại',
            'course.exists'=>'Mã khóa học không tồn tại',

        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        scheduleM::create(['idTeacher'=>$request->teacher,'schedule'=>$request->times,'idcourse'=>$request->course]);
        $result = DB::Table('class_schedule')
        ->join('courses','class_schedule.idcourse','=','courses.id')->join('course_cates','courses.idCourseCate','=','course_cates.id')
        ->select('class_schedule.*','courses.name as coursename','course_cates.name as catename')->paginate(5);
        return response()->json(['check'=>true,'schedules'=>$result]);
    }
    //===================================================
    public function allcourses(){
        $result= DB::Table('courses')
        ->join('course_cates','courses.idCourseCate','=','course_cates.id')
        ->join('edu_tbl','course_cates.idEdu','=','edu_tbl.id')
        ->select('courses.id as id','courses.name as name','courses.created_at as created_at','course_cates.name as catename','edu_tbl.name as eduname')
        ->get();
        return response()->json($result);
    }
    // =================================================
    public function getCoursesEdit($id){
        $result = DB::Table('courses')->where('id',$id)->get();
        return response()->json($result);
    }
    // =========================================

    // public function getCourseCate (Request $request){

    // }
    // =========================================
    public function single($id)
    {
        $result = DB::Table('courses')->where('courses.id',$id)->get();
        return response()->json($result);
    }
    // ==========================================
    public function getCourses ($id,courseCateM $courseCateM){
        $result = DB::Table('courses')->where('idCourseCate','=',$id)->paginate(5);
        $grades = DB::table('courses')->where('idCourseCate','=',$id)->distinct()->select('Grade')->get();
        return response()->json(['result'=>$result,'grades'=>$grades]);
    }
    // ==============================================================
    public function index (courseCateM $courseCateM,Request $request){
        $Validator = Validator::make($request->all(), [
            'id'=>'required|exists:edu_tbl,id'
        ],[
            'id.required'=>'Mã loại giáo dục chưa nhận được',
            'id.exists'=>'Mã loại giáo dục không tồn tại',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
         $result = DB::Table('course_cates')->where('idEdu','=',$request->id)->get();
         return response()->json($result);
        }
    /**
     * Display a listing of the resource.
     */
    public function createCourseCate(courseCateM $courseCateM,Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'name'=>'required|unique:course_cates,name',
            'idEdu'=>'required|exists:edu_tbl,id'
        ],[
            'name.required'=>'Chưa nhận được loại hình lớp học',
            'name.unique'=>'Loại hình lớp học đã tồn tại',
            'idEdu.required'=>'Mã loại giáo dục chưa nhận được',
            'idEdu.exists'=>'Mã loại giáo dục không tồn tại',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        courseCateM::create(['name'=>$request->name,'idEdu'=>$request->idEdu,'created_at'=>now()]);
        $result = DB::Table('course_cates')->where('idEdu',$request->idEdu)->get();
        return response()->json(['check'=>true,'result'=>$result]);
    }
    
    // ==================================================

    public function editCourseCate(Request $request,courseCateM $courseCateM)
    {
        $Validator = Validator::make($request->all(), [
            'id'=>'required|exists:course_cates,id',
            'name'=>'required|unique:course_cates,name',
        ],[
            'id.required'=>'Mã loại chưa nhận được',
            'id.exists'=>'Mã loại không tồn tại',
            'name.required'=>'Chưa nhận được loại hình lớp học',
            'name.unique'=>'Loại hình lớp học đã tồn tại',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        courseCateM::where('id',$request->id)->update(['name'=>$request->name,'updated_at'=>now()]);
        $idEdu = courseCateM::where('id',$request->id)->value('idEdu');
        $result = DB::Table('course_cates')->where('idEdu',$idEdu)->get();
        return response()->json(['check'=>true,'result'=>$result]);
    }
     /**
     * Show the form for creating a new resource.
     */
    public function switchCate(Request $request,courseCateM $courseCateM)
    {
        $Validator = Validator::make($request->all(), [
            'id'=>'required|exists:course_cates,id',
            
        ],[
            'id.required'=>'Mã loại chưa nhận được',
            'id.exists'=>'Mã loại không tồn tại',
           
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $old = courseCateM::where('id',$request->id)->value('status');
        $idEdu = courseCateM::where('id',$request->id)->value('idEdu');
        if($old==0){
            courseCateM::where('id',$request->id)->update(['status'=>1,'updated_at'=>now()]);
        }else{
            courseCateM::where('id',$request->id)->update(['status'=>0,'updated_at'=>now()]);
        }
        $result = DB::Table('course_cates')->where('idEdu',$idEdu)->get();
        return response()->json(['check'=>true,'result'=>$result]);
    }
     /**
     * Show the form for creating a new resource.
     */
    public function deleteCate(Request $request,courseCateM $courseCateM)
    {
        $Validator = Validator::make($request->all(), [
            'id'=>'required|exists:course_cates,id',
           
        ],[
            'id.required'=>'Mã loại chưa nhận được',
            'id.exists'=>'Mã loại không tồn tại',
        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $check = DB::Table('courses')->where('idCourseCate',$request->id)->count('id');
        if($check!=0){
            return response()->json(['check'=>false,'msg'=>"Có khóa học trong loại"]);
        }else{
            $idEdu = courseCateM::where('id',$request->id)->value('idEdu');
            courseCateM::where('id',$request->id)->delete();
            $result = DB::Table('course_cates')->where('idEdu','=',$idEdu)->get();
            return response()->json(['check'=>true,'result'=>$result]);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function createCourse(Request $request,courseCateM $courseCateM,courseM $courseM)
    {
        $Validator = Validator::make($request->all(), [
            'name'=>'required',
            'summary'=>'required',
            'price'=>'required|numeric|min:0',
            'discount'=>'required|numeric|min:0',
            'file'=>'required',
            'duration'=>'required|numeric|min:0',
            'Grade'=>'required',
            'idCourseCate'=>'required|exists:course_cates,id',
            'detail'=>'required'
        ],[
            'Grade.required'=>'Thiếu khối lớp của khóa học',
            'name.required'=>'Chưa nhận được tên khóa học',
            'file.required'=>'Chưa nhận được hình ảnh khóa học',
            'name.unique'=>'Tên khóa học bị trùng lặp',
            'summary.required'=>'Chưa nhận được tóm tắt khóa học',
            'price.required'=>'Chưa nhận được giá khóa học',
            'price.numeric'=>'Giá khóa học không hợp lệ',
            'price.min'=>'Giá khóa học không hợp lệ',
            'discount.required'=>'Chưa nhận được giảm giá',
            'discount.numeric'=>'Giảm giá khóa học không hợp lệ',
            'discount.min'=>'Giảm giá khóa học không hợp lệ',
            'idCourseCate.required'=>'Chưa nhận được mã loại khóa học',
            'idCourseCate.exists'=>'Mã loại khóa học không tồn tại',
            'detail.required'=>'Chưa nhận được module khóa học',

        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        $filetype = str_replace("image/","",$_FILES['file']['type']);
        $accept = ['gif', 'jpeg', 'jpg', 'png', 'svg', 'jfif', 'JFIF', 'blob', 'GIF', 'JPEG', 'JPG', 
        'PNG', 'gif', 'JFIF', 'SVG', 'webpimage', 'WEBIMAGE', 'WEBP', 'SVG', 'WEBIMAGE', 'webp', 'WEBP'];
        if (!in_array($filetype, $accept)) {
            return response()->json(['check'=>false,'msg'=>"File không phải file hình ảnh "]);
        }
        $now=now()->timestamp;
        $temp = explode(".", $_FILES['file']['name']);
        $newfilename = $now. '.' . end($temp);
        $tmp_name = $_FILES['file']['tmp_name'];
        move_uploaded_file($tmp_name,'images/'. $newfilename);
        courseM::create(['name'=>$request->name,'summary'=>$request->summary,'Grade'=>$request->Grade,'image'=>$newfilename,'price'=>$request->price,'discount'=>$request->discount,'idCourseCate'=>$request->idCourseCate,'duration'=>$request->duration,'detail'=>$request->detail,'created_at'=>now()]);
        $result=DB::Table('courses')->get();
        return response()->json(['check'=>true,'courses'=>$result]);
    }
        /**
     * Store a newly created resource in storage.
     */
    public function editCourse(Request $request,courseCateM $courseCateM,courseM $courseM)
    {
        $Validator = Validator::make($request->all(), [
            'name'=>'required',
            'Grade'=>'required',
            'summary'=>'required',
            'price'=>'required|numeric|min:0',
            'file'=>'required',
            'Grade.required'=>'Thiếu khối lớp của khóa học',
            'discount'=>'required|numeric|min:0',
            'duration'=>'required|numeric|min:0',
            'idCourseCate'=>'required|exists:course_cates,id',
            'detail'=>'required',
            'id'=>'required|exists:courses,id'
        ],[
            'id.required'=>"Chưa nhận được mã khóa học",
            'id.required'=>"Chưa nhận được mã khóa học",
            'name.required'=>'Chưa nhận được tên khóa học',
            'summary.required'=>'Chưa nhận được tóm tắt khóa học',
            'file.required'=>'Chưa nhận được hình ảnh khóa học',
            'price.required'=>'Chưa nhận được giá khóa học',
            'price.numeric'=>'Giá khóa học không hợp lệ',
            'price.min'=>'Giá khóa học không hợp lệ',
            'discount.required'=>'Chưa nhận được giảm giá',
            'discount.numeric'=>'Giảm giá khóa học không hợp lệ',
            'discount.min'=>'Giảm giá khóa học không hợp lệ',
            'idCourseCate.required'=>'Chưa nhận được mã loại khóa học',
            'idCourseCate.exists'=>'Mã loại khóa học không tồn tại',
            'detail.required'=>'Chưa nhận được module khóa học',

        ]);
        if ($Validator->fails()) {
            return response()->json(['check' => false, 'msg' => $Validator->errors()]);
        }
        if(isset($_FILES['file']['name'])){
            $image = courseM::where('id',$request->id)->value('image');
            $check=courseM::where('image',$image)->count('id');
            if($check==1){
    
                File::delete(public_path('images/'.$image));
            }
            $now=now()->timestamp;
            $temp = explode(".", $_FILES['file']['name']);
            $newfilename = $now. '.' . end($temp);
            $tmp_name = $_FILES['file']['tmp_name'];
            move_uploaded_file($tmp_name,'images/'. $newfilename);
            courseM::where('id',$request->id)->update(['name'=>$request->name,'summary'=>$request->summary,'Grade'=>$request->Grade,'image'=>$newfilename,'duration'=>$request->duration,'price'=>$request->price,'discount'=>$request->discount,'idCourseCate'=>$request->idCourseCate,'detail'=>$request->detail,'created_at'=>now()]);
            $result=DB::Table('courses')->get();
            return response()->json(['check'=>true,'courses'=>$result]);
        }else{
            courseM::where('id',$request->id)->update(['name'=>$request->name,'summary'=>$request->summary,'Grade'=>$request->Grade,'duration'=>$request->duration,'price'=>$request->price,'discount'=>$request->discount,'idCourseCate'=>$request->idCourseCate,'detail'=>$request->detail,'created_at'=>now()]);
            $result=DB::Table('courses')->get();
            return response()->json(['check'=>true,'courses'=>$result]);
        }
        
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
    public function show(courseCateM $courseCateM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, courseCateM $courseCateM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(courseCateM $courseCateM)
    {
        //
    }
}