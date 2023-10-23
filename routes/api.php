<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControllers;
use App\Http\Controllers\EduController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\scheduleControlller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// ==============================================================
Route::post('role', [UserControllers::class,'createRole']);
Route::get('role', [UserControllers::class,'getRoles']);
Route::post('switchrole', [UserControllers::class,'switchrole']);
Route::post('editRole', [UserControllers::class,'editRole']);
Route::post('deleteRole', [UserControllers::class,'deleteRole']);
// ==============================================================
Route::post('users', [UserControllers::class,'createUser']);
Route::post('editUser', [UserControllers::class,'editUser']);
Route::post('switchUser', [UserControllers::class,'switchUser']);
Route::get('users', [UserControllers::class,'getUsers']);
Route::get('users1', [UserControllers::class,'getUsers1']);
Route::post('checkLogin', [UserControllers::class,'checkLogin']);
Route::post('checkAdmin', [UserControllers::class,'checkLoginAdmin']);
Route::get('getTeacher', [UserControllers::class,'getTeacher']);

// ==========================
Route::post('contact', [UserControllers::class,'sendContact']);

// ==============================================================
Route::post('createEdu', [EduController::class,'store']);
Route::get('edu', [EduController::class,'index']);
Route::post('editEdu', [EduController::class,'edit']);
Route::post('deleteEdu', [EduController::class,'destroy']);
Route::post('switchEdu', [EduController::class,'switchEdu']);
// ==============================================================
Route::post('createCate', [CourseController::class,'createCourseCate']);
Route::post('editCate', [CourseController::class,'editCourseCate']);
Route::post('deleteCate', [CourseController::class,'deleteCate']);
Route::post('switchCate', [CourseController::class,'switchCate']);
Route::get('cate', [CourseController::class,'index']);
// ============================================================
Route::post('course', [CourseController::class,'createCourse']);
Route::get('course/{id}', [CourseController::class,'getCourses']);
Route::get('singlecourse/{id}',[CourseController::class,'single']);
Route::get('getEditCourse/{id}', [CourseController::class,'getCoursesEdit']);
Route::post('editCourse', [CourseController::class,'editCourse']);
Route::get('allcourses', [CourseController::class,'allcourses']);
// ============================================================
Route::post('addSchedule', [CourseController::class,'addSchedule']);
Route::get('getSchedule', [CourseController::class,'getSchedule']);
Route::get('allSchedule', [CourseController::class,'allSchedule']);
//====================================================
Route::get('getEducations', [EduController::class,'getEducations']);
Route::get('courses', [CourseController::class,'getCoursesUser']);
Route::get('coursecates/{id}', [CourseController::class,'coursecates']);
Route::get('duplicateCourse/{id}', [CourseController::class,'duplicateCourse']);
//====================================================
Route::get('getCurrentCourses', [CourseController::class,'getCurrentCourses']);
Route::get('getSingleCourses/{id}', [CourseController::class,'getSingleCourses']);
Route::get('getCourseCate/{id}', [CourseController::class,'getCourseCate']);
Route::get('getClass/{id}', [CourseController::class,'getClass']);
Route::post('getCourseClass', [CourseController::class,'getCourseClass']);
//====================================================
Route::post('submitBill', [BillController::class,'store']);
Route::post('createClass',[scheduleControlller::class,'store']);