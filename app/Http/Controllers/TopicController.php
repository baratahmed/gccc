<?php

namespace App\Http\Controllers;

use App\Http\Resources\TopicResource;
use App\Models\Attendance;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TopicController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function($request,$next){
            $this->user = Auth::guard('sanctum')->user();
            return $next($request);
        });
    }

    public function simpleTopics()
    {   
        return Topic::select('id','topic','instructions','points','group','section','shift','session','due_date')->orderBy('id','desc')->get();
    }

    public function index()
    {
        if(is_null($this->user) || !$this->user->can('topic.read')){
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

    public function fetchTopicsForStudents(Request $request)
    {
        if(is_null($this->user) || !$this->user->can('topic.read')){
            return send_msg('Unauthorized Access', false, 403);
        }
         $topics = Topic::where('group',$request->group)
                ->where('section',$request->section)
                ->where('shift',$request->shift)
                ->where('session',$request->session)
                ->orderBy('id','desc')
                ->paginate(50);
                
        return TopicResource::collection($topics);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
        if(is_null($this->user) || !$this->user->can('topic.create')){
            return send_msg('Unauthorized Access', false, 403);
        }

        $topic = new Topic();
        $topic->group = $request->group;
        $topic->section = $request->section;
        $topic->shift = $request->shift;
        $topic->session = $request->session;
        $topic->due_date = Carbon::parse($request->due_date)->addHours(6)->toDateString();
        $topic->topic = $request->topic;
        $topic->instructions = $request->instructions;
        $topic->points = $request->points;
        $save_url = null;
        if($request->has('image')){
            $base64Image = $request->input('image');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $image = substr($base64Image, strpos($base64Image, ',') + 1);
                $image = base64_decode($image);
                $extension = $type[1];
                $name_gen = 'TOPIC_'.hexdec(uniqid()).'.'.$extension;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($image);
                $img->save(base_path('public/img/uploads/'.$name_gen));
                $save_url = 'img/uploads/'.$name_gen;
            }
            
        }
        $topic->image = $save_url ?? "img/uploads/topic.jpg";
        $topic->save();

        return send_msg("Topic Created Successfully!", true, 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new TopicResource(Topic::find($id));
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
        if(is_null($this->user) || !$this->user->can('topic.update')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $topic = Topic::find($request->id);
        $topic->group = $request->group;
        $topic->section = $request->section;
        $topic->shift = $request->shift;
        $topic->session = $request->session;
        $topic->due_date = strlen($request->due_date) > 12 ? Carbon::parse($request->due_date)->addHours(6)->toDateString() : $request->due_date;
        $topic->topic = $request->topic;
        $topic->instructions = $request->instructions;
        $topic->points = $request->points;
        $save_url = null;
        if($request->has('image') && strlen($request->image) > 230){
            // if(env('APP_ENV') == 'production'){
            //     @unlink('public/'.$user->image);
            // }else{
            //     @unlink($user->image);
            // }
            @unlink(public_path($topic->image));

            $base64Image = $request->input('image');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $image = substr($base64Image, strpos($base64Image, ',') + 1);
                $image = base64_decode($image);
                $extension = $type[1];
                $name_gen = 'TOPIC_'.hexdec(uniqid()).'.'.$extension;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($image);
                $img->save(base_path('public/img/uploads/'.$name_gen));
                $save_url = 'img/uploads/'.$name_gen;
            }
            
        }
        if(isset($save_url)){
            $topic->image = $save_url;
        }  
        $topic->save();

        return send_msg("Topic Updated Successfully!", true, 200);
    }

    public function destroy(Request $request, $id)
    {
        if(is_null($this->user) || !$this->user->can('topic.delete')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $topic = Topic::find($id);
        $topic->delete();
        if($topic->image != 'img/uploads/topic.jpg'){
            @unlink(public_path($topic->image));
        }
        return send_msg('Delete Success', true, 200);
    }

    public function multipleDelete()
    {
        if(is_null($this->user) || !$this->user->can('topic.delete')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $topics = Topic::whereIn('id',request('ids'))->get();
        foreach ($topics as $topic) {
            $topic->delete();
            if($topic->image != 'img/uploads/topic.jpg'){
                @unlink(public_path($topic->image));
            }
        }
        return send_msg('Delete Success', true, 200);

    }
}
