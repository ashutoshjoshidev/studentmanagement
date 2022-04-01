<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\Api\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'auth']);
Route::post('student/registration', [StudentController::class, 'registration']);
Route::post('teacher/registration', [TeacherController::class, 'registration']);


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('logout', [AuthController::class, 'logout']);

    /**** Student Routes ****/
    Route::group(['middleware' => 'studentAuth', 'prefix' => 'student', 'as' => 'student.'], function () {
        Route::put('/update', [StudentController::class, 'update']);
        Route::post('/changeAvatar', [StudentController::class, 'changeAvatar']);
    });

    /**** Student Routes ****/
    Route::group(['middleware' => 'teacherAuth', 'prefix' => 'teacher', 'as' => 'teacher.'], function () {
        Route::put('/update', [TeacherController::class, 'update']);
        Route::post('/changeAvatar', [TeacherController::class, 'changeAvatar']);
    });

    /**** Admin Routes ****/
    Route::group(['middleware' => 'adminAuth', 'prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/approve/{id}', [AdminController::class, 'approveUser']);
        Route::get('/assignTeacher/{teacher_id}/{student_id}', [AdminController::class, 'assignTeacher']);
    });
});
