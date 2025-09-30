<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentDashboardResource;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\StudentSections;
use App\Models\StudentSubjects;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function($request,$next){
            $this->user = Auth::guard('sanctum')->user();
            return $next($request);
        });
    }

    public function index(Request $request){

        if(is_null($this->user) || !$this->user->can('dashboard.read')){
            return send_msg('Unauthorized Access', false, 403);
        }

        $auth_user = User::select('id','name','phone','roll_no')->with('student_info:id,user_id,group_id,session_id')->find(auth()->id());
        $from = Carbon::parse(request('from'))->addHours(6)->toDateString();
        $to = Carbon::parse(request('to'))->addHours(6)->toDateString();

        if($auth_user->role()->name != 'Student'){
            return [
                'total_teachers' => 3,
                'total_students' => 2030,
                'total_sections' => 7,
                'total_faculties' => 1,

                'total_assignments' => 223,
                'total_attendances' => 5985,
                'active_users' => 2000,
                'inactive_users' => 30,
            ];
        }else{
            $sessionId = $auth_user->student_info->session_id;
            $groupId = $auth_user->student_info->group_id;
            $section_ids = StudentSections::with('section:id,name,shift')->where('user_id',auth()->id())->pluck('section_id');
            $userId = $auth_user->id;

            $studentSubjects = StudentSubjects::where('student_id', $userId)->first();

            if($groupId == 1){
                $subjectIds = [1,2,3,4,5,6,7];
            }elseif($groupId == 2){
                $subjectIds = [1,2,4,8,9,10,11];
            }else{
                $subjectIds = [
                    $studentSubjects->first_subject_id,
                    $studentSubjects->second_subject_id,
                    $studentSubjects->third_subject_id,
                    $studentSubjects->fourth_subject_id,
                ];

                $subjectIds = array_filter($subjectIds);

                $subjectIds = array_merge([1, 2, 3], $subjectIds);
            }

            $result = [];

            foreach($subjectIds as $subjectId){
                $subject = Subject::find($subjectId);
                $filteredAttendances = Attendance::where('subject_id', $subjectId)
                    ->where('session_id', $sessionId)
                    ->where('group_id', $groupId)
                    ->whereHas('sections', function($q)use($section_ids){
                        $q->whereIn('section_id',$section_ids);
                    })
                    ->whereBetween('date', [$from, $to]);

                $total_classes = $filteredAttendances->selectRaw("SUM(CASE WHEN type = 'LAB' THEN 2 ELSE 1 END) as total")->value('total');

                $present_classes = $filteredAttendances
                    ->whereJsonContains('std_ids', $userId)
                    ->selectRaw("SUM(CASE WHEN type = 'LAB' THEN 2 ELSE 1 END) as total")->value('total');

                $result[$subject->name] = [
                    'total_classes' => $total_classes,
                    'present' => $present_classes,
                    'percentage' => $total_classes > 0 ? round(($present_classes / $total_classes) * 100, 2) : 0,
                ];
            }
            return $result;


            // $total_classes = DB::table('attendances')
            //             ->where('section_id',$auth_user->student_info->section_id)
            //             ->whereBetween('date',[$from,$to])
            //             ->count();

            // $present = DB::table('attendances')
            //             ->where('section_id',$auth_user->student_info->section_id)
            //             ->whereBetween('date',[$from,$to])
            //             ->whereJsonContains('std_ids', $auth_user->id)
            //             ->count();

            // $last_assignment = Assignment::where('student_id',$auth_user->id)->latest()->first();
            // $total_assignments = Assignment::where('student_id',$auth_user->id)->count();
            // $highest_mark = Assignment::where('student_id',$auth_user->id)->max('marks');
            // $lowest_mark = Assignment::where('student_id',$auth_user->id)->min('marks');
            // $average_marks = Assignment::where('student_id',$auth_user->id)->avg('marks');



            // return [
            //     'present' => $present,
            //     'absent' => $total_classes - $present,
            //     'percentage' => $total_classes > 0 ? round(($present/$total_classes)*100,2) : 0,
            //     'last_result' => (isset($last_assignment) && !is_null($last_assignment->marks)) ? $last_assignment->marks : 'Not Set',

            //     'total_assignments' => $total_assignments,
            //     'highest_mark' => $highest_mark ?? 'N/A',
            //     'lowest_mark' => $lowest_mark ?? 'N/A' ,
            //     'average_mark' => round($average_marks,2),
            // ];
        }
    }
}
