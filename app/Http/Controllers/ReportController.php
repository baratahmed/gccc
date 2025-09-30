<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassReportResource;
use App\Http\Resources\TodayReportResource;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Session;
use App\Models\StudentSections;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Assign;

class ReportController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function($request,$next){
            $this->user = Auth::guard('sanctum')->user();
            return $next($request);
        });
    }

    public function classReport(Request $request){

        // {"today":"2025-09-20T14:52:51.582Z","session_id":"1","shift":"DAY"}
        $today = Carbon::parse($request->today)->addHours(6)->toDateString();

        $theory_result = [];
        $lab_result = [];

        $session = Session::find($request->session_id);

        $theory_sections = Section::select('id','group_id','name','shift','type','for')->whereIn('type',['THEORY','BOTH'])->where('shift',$request->shift)->where('for',$session->type)->get();
        $lab_sections = Section::select('id','group_id','name','shift','type','for')->whereIn('type',['LAB','BOTH'])->where('shift',$request->shift)->where('for',$session->type)->get();
            

        foreach ($theory_sections as $key => $section) {
            $filteredAttendances = Attendance::where('session_id', $request->session_id)
                                            ->where('type', 'THEORY')
                                            ->whereHas('sections', function($q)use($section){
                                                $q->where('section_id',$section->id);
                                            })
                                            ->where('date', $today)
                                            ->get()
                                            ->map(function($attendance) use ($section) {
                                                $attendance->section_id = $section->id;
                                                return $attendance;
                                            });

            $theory_result[] = [
                'section_name' => $section->name,
                'attendances' => ClassReportResource::collection($filteredAttendances)
            ];
        }

        foreach ($lab_sections as $key => $section) {
            $filteredAttendances = Attendance::where('session_id', $request->session_id)
                                            ->where('type','LAB')
                                            ->whereHas('sections', function($q)use($section){
                                                $q->where('section_id',$section->id);
                                            })
                                            ->where('date', $today)
                                            ->get()
                                            ->map(function($attendance) use ($section) {
                                                $attendance->section_id = $section->id;
                                                return $attendance;
                                            });
                                            
            $lab_result[] = [
                'section_name' => $section->name,
                'attendances' => ClassReportResource::collection($filteredAttendances)
            ];
        }
        return [
            'theory' => $theory_result,
            'lab' => $lab_result,
        ];



        // $sections = Section::select('id','group_id','name','shift','type')
        //                     ->whereNotIn('id',[4,5,6,7])    
        //                     ->where('shift',$request->shift)->orderBy('id','asc')->get();

        // foreach ($sections as $key => $section) {
        //     $filteredAttendances = Attendance::where('session_id', $request->session_id)
        //                                     ->where('type', 'THEORY')
        //                                     ->whereHas('sections', function($q)use($section){
        //                                         $q->where('section_id',$section->id);
        //                                     })
        //                                     ->where('date', $today)->get();

        //     $result[] = [
        //         'section_name' => $section->name,
        //         'attendances' => ClassReportResource::collection($filteredAttendances)
        //     ];
        // }


        return $result;


    }

    public function teacherReports(Request $request){

        // if(is_null($this->user) || !$this->user->can('report.teacher')){
        //     return send_msg('Unauthorized Access', false, 403);
        // }

        $from = Carbon::parse(request('from'))->addHours(6)->toDateString();
        $to = Carbon::parse(request('to'))->addHours(6)->toDateString();
        // return $teachers = User::select('id','name','phone')
        //                     ->whereHas('roles', function ($q) {
        //                         $q->where('name', 'Teacher');
        //                     })
        //                     ->with('roles:name')
        //                     ->with(['attendances' => function($q)use($from,$to){
        //                         $q->select('id','user_id','date','session_id')
        //                           ->where('session_id',request('session_id'))  
        //                           ->whereBetween('date', [$from, $to]);
        //                     }])
        //                     ->get();


            $result = [];
            $subjects = Subject::get();
            foreach($subjects as $subject){
                
                $teachersIDs = $subject->teachers->pluck('user_id');
                $teachers = User::whereIn('id',$teachersIDs);

                $result[] = [
                    'subject_name' => $subject->name,
                    'teachers' => $teachers->select('id','name','phone')
                                    ->get()
                                     ->map(function ($teacher) use ($subject, $from, $to) {
                                        $teacher->total_classes = Attendance::where('subject_id', $subject->id)
                                            ->where('user_id', $teacher->id)
                                            ->where('session_id', request('session_id'))
                                            ->whereBetween('date', [$from, $to])
                                            ->selectRaw("SUM(CASE WHEN type = 'LAB' THEN 2 ELSE 1 END) as total")
                                            ->value('total');

                                        return $teacher;
                                    }),
                ];
            }
            return $result;

    }

    public function attendanceReports(Request $request){
        // if(is_null($this->user) || !$this->user->can('report.attendance')){
        //     return send_msg('Unauthorized Access', false, 403);
        // }
        // return $request->all();

        // {
        //     "from": "2025-08-31T18:00:00.000Z",
        //     "to": "2025-09-13T08:02:09.316Z",
        //     "session_id": "1",
        //     "group_id": "1",
        //     "shift": "DAY",
        //     "subject_id": "2"
        // }

        $from = Carbon::parse(request('from'))->addHours(6)->toDateString();
        $to = Carbon::parse(request('to'))->addHours(6)->toDateString();

        // $section_ids = StudentSections::select('section_id')->whereIn('user_id',$students->pluck('id'))->groupBy('section_id')->pluck('section_id');

        $subject = Subject::find($request->subject_id);

        $session = Session::find($request->session_id);

 
        $students = User::select('id','name','roll_no')
                ->whereHas('student_info', function ($q) {
                    $q->where('session_id',request('session_id'));
                    $q->where('group_id',request('group_id'));
                })
                    ->whereHas('sections.section', function ($q) {
                    $q->where('shift',request('shift'));
                });
                
        return [
            'students' => $students->with('sections.section:id,name,shift,type,for')->get(),
            'attendances' => Attendance::with('sections.section:id,name,shift,type,for')
                            ->where('session_id',request('session_id'))
                            ->where('group_id',request('group_id'))
                            ->where('subject_id',request('subject_id'))
                            ->whereBetween('date', [$from, $to])->get() ?? null
        ];
    
    }


    public function assignmentReports(){
        // if(is_null($this->user) || !$this->user->can('report.assignment')){
        //     return send_msg('Unauthorized Access', false, 403);
        // }

        $assignments = Assignment::where('topic_id',request('topic_id'))
                        ->whereHas('topic', function ($q) {
                            $q->where('section',request('section'));
                            $q->where('shift',request('shift'));
                        })
                        ->with('student:id,name,roll_no')
                        ->get();
        return [
            'topic' => Topic::find(request('topic_id')) ?? null,
            'assignments' => $assignments
        ];
    }
}
