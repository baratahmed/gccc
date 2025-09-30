<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Attendance;
use App\Models\CounsellorStudent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CounsellingController extends Controller
{

    public function fetchStudentsForCounsellor(Request $request){

        $request->validate([
            'session_id' => 'required|exists:sessions,id',
            'section_id' => 'required|exists:sections,id',
            'teacher_id' => 'nullable|exists:users,id',
            'per_page' => 'nullable|integer',
            'search' => 'nullable|string|max:255',
        ]);

        $perPage = $request->perPage ?? 50;
     
        $session_id = $request->session_id;
        $section_id = $request->section_id;

        $students = User::query()
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Student');
                })
                ->when(request('search'), function($q, $search){
                    return $q->where('name','LIKE','%'.$search.'%')
                             ->orWhere('roll_no','LIKE','%'.$search.'%')
                             ->orWhere('phone','LIKE','%'.$search.'%')
                             ->orWhere('email','LIKE','%'.$search.'%');
                })
                ->whereHas('student_info', function($q) use ($session_id){
                    $q->where('session_id', $session_id);
                })
                ->whereHas('sections', function($q) use ($section_id){
                    $q->where('section_id', $section_id);
                })
                ->orderBy('id','desc')
                ->paginate($perPage);
                
        return StudentResource::collection($students);
    }

    public function assignStudentsToCounsellors(Request $request){
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
            'teacher_id' => 'required|exists:users,id',
        ]);

        $student_ids = $request->student_ids;
        $teacher_id = $request->teacher_id;

        foreach($student_ids as $student_id){
            CounsellorStudent::where('student_id', $student_id)->delete();
            CounsellorStudent::create([
                'teacher_id' => $teacher_id,
                'student_id' => $student_id,
            ]); 
        }

        return send_msg('Students assigned to counsellor successfully', true, 200);
    }

    public function teacherCounselling(Request $request){
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
        ]);

        $data = CounsellorStudent::select('id','student_id','teacher_id')
                ->with('student:id,name,roll_no,phone,email')
                ->where('teacher_id', $request->teacher_id)
                ->orderBy('id','desc')
                ->get();                
        return $data;
    }

    public function viewDetails(Request $request){
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $student = User::select('id','name','phone','roll_no')->with('student_info:id,user_id,group_id,section_id,session_id')->find($request->student_id);
        $from = Carbon::parse(request('from'))->addHours(6)->toDateString();
        $to = Carbon::parse(request('to'))->addHours(6)->toDateString();


        $sectionId = $student->student_info->section_id;
        $sessionId = $student->student_info->session_id;
        $userId = $student->id;

        // Bangla
        $filteredBanglaAttendances = Attendance::where('subject_id', 1)
            ->where('section_id', $sectionId)
            ->where('session_id', $sessionId)
            ->whereBetween('date', [$from, $to]);

        $total_bangla_classes = $filteredBanglaAttendances->count();
        $present_bangla_classes = $filteredBanglaAttendances->whereJsonContains('std_ids', $userId)->count();


        // English
        $filteredEnglishAttendances = Attendance::where('subject_id', 2)
            ->where('section_id', $sectionId)
            ->where('session_id', $sessionId)
            ->whereBetween('date', [$from, $to]);

        $total_english_classes = $filteredEnglishAttendances->count();
        $present_english_classes = $filteredEnglishAttendances->whereJsonContains('std_ids', $userId)->count();


        // Ict
        $filteredIctAttendances = Attendance::where('subject_id', 3)
            ->where('section_id', $sectionId)
            ->where('session_id', $sessionId)
            ->whereBetween('date', [$from, $to]);

        $total_ict_classes = $filteredIctAttendances->count();
        $present_ict_classes = $filteredIctAttendances->whereJsonContains('std_ids', $userId)->count();


        return [
            "Bangla" => [
                'total_classes' => $total_bangla_classes,
                'present' => $present_bangla_classes,
                'percentage' => $total_bangla_classes > 0 ? round(($present_bangla_classes / $total_bangla_classes) * 100, 2) : 0,
            ],
            "English" => [
                'total_classes' => $total_english_classes,
                'present' => $present_english_classes,
                'percentage' => $total_english_classes > 0 ? round(($present_english_classes / $total_english_classes) * 100, 2) : 0,
            ],
            "ICT" => [
                'total_classes' => $total_ict_classes,
                'present' => $present_ict_classes,
                'percentage' => $total_ict_classes > 0 ? round(($present_ict_classes / $total_ict_classes) * 100, 2) : 0,
            ]
        ];
       
    }
}
