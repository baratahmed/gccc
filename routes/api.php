<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SimpleFetchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\CounsellingController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(TestController::class)->group(function(){
    Route::get('/test','index');
});

Route::controller(AuthController::class)->group(function(){
    Route::post('/login','login');
    Route::get('/instant/role/permissions/{user_id}','getInstantRolePermissions');
    Route::post('/change/password','changePassword');
    Route::post('/update/profile','updateProfile');
    Route::post('/update/profile/image','updateProfileImage');
});

Route::controller(PdfController::class)->group(function(){
    Route::get('/test/pdf','index');
});
    
Route::middleware('auth:sanctum','check_authorization')->group(function () {
    Route::controller(AuthController::class)->group(function(){
        Route::post('/logout','logout');
        Route::get('/me','user');
    });
    Route::controller(DashboardController::class)->group(function(){
        Route::get('/dashboard','index');
    });

    Route::controller(SimpleFetchController::class)->group(function(){
        Route::get('/simple/counsellors','fetchAllCounsellors');
        Route::get('/simple/all_groups','fetchAllGroups');
        Route::get('/simple/groups','fetchGroups');
        Route::get('/simple/all_sections','fetchAllSections');
        Route::get('/simple/theory_sections/{session_id}','fetchTheorySections');
        Route::get('/simple/sections','fetchSections');
        Route::get('/simple/all_sessions','fetchAllSessions');
        Route::get('/simple/all_subjects','fetchAllSubjects');
        Route::get('/simple/subjects/{group_id}','fetchSubjects');
        Route::get('/simple/teachers','fetchTeachers');
        Route::get('/simple/setting/{key}','fetchDates');
    });

    Route::apiResources([
        'roles' => RoleController::class,
        'students' => StudentController::class,
        'teachers' => TeacherController::class,
        'admins' => AdminController::class,
        'attendances' => AttendanceController::class,
        'topics' => TopicController::class,
        'assignments' => AssignmentController::class,
        'sessions' => SessionController::class,
    ]);
    
    Route::controller(StudentController::class)->group(function(){
        Route::post('/update/student','update');
        Route::delete('/student/multiple-delete','multipleDelete');
    });
    Route::controller(TeacherController::class)->group(function(){
        Route::post('/update/teacher','update');
        Route::delete('/teacher/multiple-delete','multipleDelete');
    });
    Route::controller(AdminController::class)->group(function(){
        Route::post('/update/admin','update');
        Route::delete('/admin/multiple-delete','multipleDelete');
    });

    Route::controller(AttendanceController::class)->group(function(){
        Route::get('/fetch/students','fetchStudents');
        Route::post('/update/attendance','update');
        Route::delete('/attendance/multiple-delete','multipleDelete');
    });

      Route::controller(FileManagerController::class)->group(function(){
        Route::post('/file_manager/upload','upload');
        Route::get('/file_manager/read/{fileable_id}','fetchFiles');
        Route::delete('/file_manager/{lastModified}/{size}','destroyFromClient');
        Route::delete('/file_manager/{fileId}','destroy');
    });

    Route::controller(TopicController::class)->group(function(){
        Route::get('/simple/topics','simpleTopics');
        Route::post('/update/topic','update');
        Route::delete('/topic/multiple-delete','multipleDelete');

        Route::get('/student/topics','fetchTopicsForStudents');
    });

    Route::controller(SessionController::class)->group(function(){
        Route::post('/update/session','update');
        // Route::get('/last/session','lastSession');
    });

    Route::controller(AssignmentController::class)->group(function(){
        Route::post('/update/assignment','update');
        Route::delete('/assignment/multiple-delete','multipleDelete');

        Route::get('/topic/assignments/{topic_id}','fetchAssignmentsOfaTopic');
        Route::post('/marking/single/assignment','markingSingleAssignment');
    });


    Route::controller(RoleController::class)->group(function(){
        Route::get('/fetch/permissions/by/group/name','fetchPermissionsByGroupName');
    });

    Route::controller(ReportController::class)->group(function(){
        Route::get('/class/report','classReport');
        Route::get('/teacher/report','teacherReports');
        Route::get('/attendance/report','attendanceReports');
        Route::get('/assignment/report','assignmentReports');
    });

    Route::controller(CounsellingController::class)->group(function(){
        Route::get('/counselling/students','fetchStudentsForCounsellor');
        Route::post('/counselling/students_assigned_to_counsellor','assignStudentsToCounsellors');
        Route::get('/counselling/techer_counselling','teacherCounselling');
        Route::get('/counselling/view_details','viewDetails');
    });

});
