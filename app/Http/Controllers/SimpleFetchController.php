<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Http\Resources\UserResource;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Section;
use App\Models\Session;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SimpleFetchController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function($request,$next){
            $this->user = Auth::guard('sanctum')->user();
            return $next($request);
        });
    }


    public function fetchAllCounsellors(){
        return User::select('id','name')
                    ->whereHas('teacher_info', function($q){
                        $q->where('is_counsellor',1);
                    })
                    ->where('is_verified',1)
                    ->get();
    }

    public function fetchAllGroups(){
        return Group::select('id','name','short_name')->get();
    }

    public function fetchGroups(Request $request){
        if($request->subject_id == 11){
            return Group::select('id','name','short_name')
                            ->whereIn('id',[2,3])
                            ->get();    
        }else{            
            return Group::select('id','name','short_name')
                        ->where('id',$request->group_id)
                        ->get();
        }
    }

    public function fetchAllSections(){
        return Section::select('id','group_id','name','shift','type')->get();
    }

    
    public function fetchTheorySections($session_id){
        $session = Session::find($session_id);

        if($session->type == '1ST_YEAR'){
            return Section::select('id','group_id','name','shift','type','for')
                                ->where('for','1ST_YEAR')
                                ->where('type','THEORY')
                                ->orWhere('type','BOTH')
                                ->get();
        }else{
            return Section::select('id','group_id','name','shift','type','for')
                                ->where('for','2ND_YEAR')
                                ->where('type','THEORY')
                                ->get();
        }
   
    }
    
    public function fetchSections(Request $request){
        // return $request->all();
        // {"session_id":"1","group_id":"1","type":"THEORY","subject_id":"3"}

        $session = Session::find($request->session_id);

        if($session->type == '1ST_YEAR'){
            // FOR 1ST YEAR
            if($request->type == 'THEORY'){
                if($request->group_id == 1){
                    return Section::select('id','group_id','name','shift','type')
                            ->where('group_id',$request->group_id)
                            ->where('type','THEORY')
                            ->where('for','1ST_YEAR')
                            ->get();
                }else{
                    return Section::select('id','group_id','name','shift','type')
                            ->where('group_id',$request->group_id)
                            ->where('type','BOTH')
                            ->where('for','1ST_YEAR')
                            ->get();
                }
            }else{
                // FOR LAB
                if($request->subject_id == 3){
                    // FOR ICT LAB
                    if($request->group_id == 1){
                        return Section::select('id','group_id','name','shift','type')
                            ->where('group_id',$request->group_id)
                            ->where('type','LAB')
                            ->where('for','1ST_YEAR_ICT')
                            ->get();
                    }else{
                        return Section::select('id','group_id','name','shift','type')
                            ->where('group_id',$request->group_id)
                            ->where('type','BOTH')
                            ->where('for','1ST_YEAR')
                            ->get();
                    }
                }else{
                    return Section::select('id','group_id','name','shift','type')
                            ->where('group_id',$request->group_id)
                            ->where('type','LAB')
                            ->where('for','1ST_YEAR')
                            ->get();
                }
            }


        }else{
            // FOR 2ND YEAR
            if($request->type == 'THEORY'){
                return Section::select('id','group_id','name','shift','type')
                            ->where('group_id',$request->group_id)
                            ->where('type','THEORY')
                            ->where('for','2ND_YEAR')
                            ->get();
            }else{
                // FOR LAB
                return Section::select('id','group_id','name','shift','type')
                            ->where('group_id',$request->group_id)
                            ->where('type','LAB')
                            ->where('for','2ND_YEAR')
                            ->get();
            }

        }
    }

    public function fetchAllSubjects(){
        return Subject::select('id','group_id','name')->get();
    }

    public function fetchSubjects($group_id){
        if($group_id == 1){
            return Subject::select('id','group_id','name')
                                        ->where('group_id',$group_id)
                                        ->orWhere('group_id',null)
                                        ->get();
        }else{
            return Subject::select('id','group_id','name')
                            ->where('group_id',$group_id)
                            ->orWhere('group_id',null)
                            ->orWhere('group_id',4)
                            ->get();
        }
    }

    public function fetchAllSessions(){
        return Session::select('id','name','type')->get();
    }

    public function fetchTeachers(){
        if(is_null($this->user) || !$this->user->can('user.read')){
            return send_msg('Unauthorized Access', false, 403);
        }
        return User::select('id','name')->whereHas(
            'roles', function($q){
                $q->where('name', 'Teacher');
            }
        )->get();
    }

    public function fetchDates($key){
        if(is_null($this->user)){
            return send_msg('Unauthorized Access', false, 403);
        }
        return Setting::select('id','key','value','updated_by')->where('key',$key)->first();
    }

}

