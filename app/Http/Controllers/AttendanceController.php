<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\AttendanceSections;
use App\Models\StudentSubjects;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function($request,$next){
            $this->user = Auth::guard('sanctum')->user();
            return $next($request);
        });
    }

    public function index()
    {
        if(is_null($this->user) || !$this->user->can('attendance.read')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $perPage = request('perPage') ?: 30;
        if(request('role') == 'Admin'){
            $attendances = Attendance::query()
                // ->when(request('search'), function($q, $search){
                //     return $q->where('name','LIKE','%'.$search.'%')
                //              ->orWhere('roll_no','LIKE','%'.$search.'%')
                //              ->orWhere('phone','LIKE','%'.$search.'%')
                //              ->orWhere('email','LIKE','%'.$search.'%');
                // })
                ->orderBy('id','desc')
                ->paginate($perPage);
        }else{
             $attendances = Attendance::query()
                // ->when(request('search'), function($q, $search){
                //     return $q->where('name','LIKE','%'.$search.'%')
                //              ->orWhere('roll_no','LIKE','%'.$search.'%')
                //              ->orWhere('phone','LIKE','%'.$search.'%')
                //              ->orWhere('email','LIKE','%'.$search.'%');
                // })
                ->where('user_id', auth()->id())
                ->orderBy('id','desc')
                ->paginate($perPage);
        }
                
        return AttendanceResource::collection($attendances);
    }

    public function fetchStudents(Request $request)
    {
        // return $request->all();
         if($request->session_id == '999'){
            return send_msg('Please Select a Session', false, 200);
        }
        
        if($request->group_id == '999'){
            return send_msg('Please Select a Group', false, 200);
        }

        if($request->section_ids == null || $request->section_ids == 'null' || $request->section_ids == ''){
            return send_msg('Please Select a Section', false, 200);
        }  
        if($request->group_id == 3 && ($request->subject_id != 1 && $request->subject_id != 2 && $request->subject_id != 3)){
            return $student_ids = StudentSubjects::where('first_subject_id',$request->subject_id)
                                            ->orWhere('second_subject_id',$request->subject_id)
                                            ->orWhere('third_subject_id',$request->subject_id)
                                            ->orWhere('fourth_subject_id',$request->subject_id)
                                            ->pluck('student_id');
            $students = User::whereIn('id',$student_ids);

            return $students->select('id','name','roll_no')
                ->whereHas('sections', function($q)use($request){
                    $q->whereIn('section_id',$request->section_ids);
                })
                ->whereHas('student_info', function($q)use($request){
                        $q->where('group_id',$request->group_id);
                        $q->where('session_id',$request->session_id);
                })
              ->get();
        }else{
            return User::select('id','name','roll_no')
                ->whereHas('sections', function($q)use($request){
                    $q->whereIn('section_id',$request->section_ids);
                })
              ->whereHas('student_info', function($q)use($request){
                    $q->where('group_id',$request->group_id);
                    $q->where('session_id',$request->session_id);
              })
              ->get();
        }

        
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
        // return $request->all();
        if($request->session_id == '999'){
            return send_msg('Please Select a Session', false, 200);
        }

        if($request->group_id == '999'){
            return send_msg('Please Select a Group', false, 200);
        }

        if($request->section_ids == null || $request->section_ids == 'null' || $request->section_ids == ''){
            return send_msg('Please Select a Section', false, 200);
        }

        if(is_null($this->user) || !$this->user->can('attendance.create')){
            return send_msg('Unauthorized Access', false, 403);
        }
 
        if(Attendance::where('subject_id',$request->subject_id)
                    ->where('session_id',$request->session_id)
                    ->where('type',$request->type)
                    ->whereHas('sections', function($q)use($request){
                            $q->whereIn('section_id',$request->section_ids);
                     })
                    ->where('date',Carbon::parse($request->date)->addHours(6)->toDateString())
                    ->exists()){
            return send_msg('Attendance already taken for this section of this subject on this date', false, 200);

        }

        $attendance = new Attendance();
        $attendance->user_id = auth()->id();
        $attendance->session_id = $request->session_id;
        $attendance->group_id = $request->group_id;
        $attendance->subject_id = $request->subject_id;
        $attendance->type = $request->type;
        $attendance->date = Carbon::parse($request->date)->addHours(6)->toDateString();
        $attendance->std_ids = json_encode($request->std_ids);
        $attendance->save();

        foreach ($request->section_ids as $key => $scetion_id) {
            $data = new AttendanceSections();
            $data->attendance_id = $attendance->id;
            $data->section_id = $scetion_id;
            $data->save();
        }

        return send_msg("Attendance Created Successfully!", true, 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance =  Attendance::with('section:id,name,shift')->find($id);

        $student_list = User::select('id','name','roll_no')
                        ->whereHas('student_info', function($q)use($attendance){
                                $q->where('group_id',$attendance->group_id);
                                $q->where('section_id',$attendance->section_id);
                                $q->where('session_id',$attendance->session_id);
                        })
                        ->get();

        return [
            'attendance' => $attendance,
            'student_list' => $student_list,
        ];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if(is_null($this->user) || !$this->user->can('attendance.update')){
            return send_msg('Unauthorized Access', false, 403);
        }

        if($request->session_id == '999'){
            return send_msg('Please select a Session', false, 200);
        }

        if($request->group_id == '999'){
            return send_msg('Please select a Group', false, 200);
        }

        if($request->section_id == '999'){
            return send_msg('Please select a Section', false, 200);
        } 
       
        $attendance = Attendance::find($request->id);
        $attendance->group_id = $request->group_id;
        $attendance->section_id = $request->section_id;
        $attendance->session_id = $request->session_id;
        $attendance->date = strlen($request->date) > 12 ? Carbon::parse($request->date)->addHours(6)->toDateString() : $request->date;
        $attendance->std_ids = json_encode($request->std_ids);
        $attendance->save();

        return send_msg("Attendance Updated Successfully!", true, 200);
    }

    public function destroy(Attendance $attendance)
    {
        if(is_null($this->user) || !$this->user->can('attendance.delete')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $attendance->delete();
        AttendanceSections::where('attendance_id',$attendance->id)->delete();

        return send_msg('Delete Success', true, 200);
    }

    public function multipleDelete()
    {
        if(is_null($this->user) || !$this->user->can('attendance.delete')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $attendances = Attendance::whereIn('id',request('ids'))->get();
        foreach ($attendances as $attendance) {
            $attendance->delete();
            AttendanceSections::where('attendance_id',$attendance->id)->delete();
        }
        return send_msg('Delete Success', true, 200);
    }
}
