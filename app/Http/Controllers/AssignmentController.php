<?php

namespace App\Http\Controllers;

use App\Http\Resources\TopicResource;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\File;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AssignmentController extends Controller
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
        if(is_null($this->user) || !$this->user->can('assignment.read')){
            return send_msg('Unauthorized Access', false, 403);
        }

        $perPage = request('perPage') ?: 30;
        $topics = Topic::query()
                ->when(request('search'), function($q, $search){
                    return $q->where('topic','LIKE','%'.$search.'%');
                })
                ->orderBy('id','desc')
                ->paginate($perPage);
                
        return TopicResource::collection($topics);
    }

    public function fetchAssignmentsOfaTopic($topic_id){
        return Assignment::with('student:id,name,roll_no')->with('files')->where('topic_id',$topic_id)->get();
    }

    public function markingSingleAssignment(Request $request){
        $assignment = Assignment::find($request->id);
        $assignment->marks = $request->marks;
        $assignment->comment = $request->comment;
        $assignment->save();

        return send_msg("Marking Done!", true, 200);
    }

    public function store(Request $request)
    {

        if(is_null($this->user) || !$this->user->can('assignment.create')){
            return send_msg('Unauthorized Access', false, 403);
        }

        if(Assignment::where('topic_id',$request->topic_id)->where('student_id',$request->student_id)->first()){
            return send_msg("Already Submitted!", false, 403);
        }

        $assignment = new Assignment();
        $assignment->topic_id = $request->topic_id;
        $assignment->student_id = $request->student_id;
        $assignment->save();

        if(isset($request->file_ids)){
            // $file_array =   explode(",",$request->file_ids);
            File::whereIn('id',$request->file_ids)->update(['fileable_id'=>$assignment->id]);
        }

        return send_msg("Assignment Submitted Successfully!", true, 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

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
        if(is_null($this->user) || !$this->user->can('assignment.update')){
            return send_msg('Unauthorized Access', false, 403);
        }
       

        return send_msg("Assignment Updated Successfully!", true, 200);
    }

    public function destroy(Request $request, $id)
    {
        if(is_null($this->user) || !$this->user->can('assignment.delete')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $assignment = Assignment::find($id);
        $assignment->delete();        
        return send_msg('Delete Success', true, 200);
    }

    public function multipleDelete()
    {
        if(is_null($this->user) || !$this->user->can('assignment.delete')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $assignments = Assignment::whereIn('id',request('ids'))->get();
        foreach ($assignments as $assignment) {
            $assignment->delete();           
        }
        return send_msg('Delete Success', true, 200);

    }
}
