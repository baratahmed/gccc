<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeacherResource;
use App\Models\TeacherInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TeacherController extends Controller
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
        if(is_null($this->user) || !$this->user->can('teacher.read')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $perPage = request('perPage') ?: 100;
        $users = User::query()
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Teacher');
                })
                ->when(request('search'), function($q, $search){
                    return $q->where('name','LIKE','%'.$search.'%')
                             ->orWhere('phone','LIKE','%'.$search.'%')
                             ->orWhere('email','LIKE','%'.$search.'%');
                })
                ->orderBy('id','asc')
                ->paginate($perPage);
                
        return TeacherResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        if($request->subject_id == '999'){
            return send_msg('Please select a subject', false, 403);
        }
        if(is_null($this->user) || !$this->user->can('teacher.create')){
            return send_msg('Unauthorized Access', false, 403);
        }

        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email:rfc|max:50|unique:users',
            'phone' => 'required|max:20',
            'image' => 'nullable|image|mimes:png,jpeg,jpg',
        ]);

        DB::beginTransaction();
        try {
                $user = new User();
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->email = $request->email; 
                $user->gender = $request->gender;                
                $user->religion = $request->religion;    
                $user->added_by = auth()->id(); 
                $user->password = bcrypt('password');
                $user->is_verified = $request->status ?? 0;
                $save_url = null;
                if($request->hasFile('image')){
                    $name_gen = 'TEACHER_'.hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($request->file('image'));
                    $img->save(base_path('public/img/users/'.$name_gen));
                    $save_url = 'img/users/'.$name_gen;
                }
                $user->image = $save_url ?? "img/users/user.jpg";
                $user->save();
                $user->assignRole('Teacher');

                $teacher_info = new TeacherInfo();
                $teacher_info->subject_id = $request->subject_id;
                $teacher_info->user_id = $user->id;
                $teacher_info->is_counsellor = $request->is_counsellor ?? 0;
                $teacher_info->save();

                DB::commit();
                return send_msg("Teacher Created Successfully!", true, 200);
    
            } catch (\Throwable $th) {
                DB::rollback();
                return send_msg("Something went wrong!", false, 200);
            }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(is_null($this->user) || !$this->user->can('teacher.read')){
            return send_msg('Unauthorized Access', false, 403);
        }
        return new TeacherResource(User::find($id));
    }

    public function update(Request $request)
    {
        if(is_null($this->user) || !$this->user->can('teacher.update')){
            return send_msg('Unauthorized Access', false, 403);
        }
        // return $request->all();  
        $request->validate([
            'id' => 'required',
            'name' => 'required|max:50',
            'email' => 'required|email:rfc|max:50|unique:users,email,'.$request->id,
            'phone' => 'required|max:20',
            'image' => 'nullable|image|mimes:png,jpeg,jpg', 
        ]);

        DB::beginTransaction();
        try {
                $user = User::find($request->id);
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->email = $request->email; 
                $user->gender = $request->gender;                
                $user->religion = $request->religion;    
                $user->added_by = auth()->id(); 
                $user->is_verified = $request->status ?? 0;
                $save_url = null;
                if($request->hasFile('image')){
                    if(env('APP_ENV') == 'production'){
                        @unlink('public/'.$user->image);
                    }else{
                        @unlink($user->image);
                    }
                    $name_gen = 'TEACHER_'.hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($request->file('image'));
                    $img->save(base_path('public/img/users/'.$name_gen));
                    $save_url = 'img/users/'.$name_gen;
                }
                if(isset($save_url)){
                    $user->image = $save_url;
                }                
                $user->save();

                $teacher_info = TeacherInfo::where('user_id',$user->id)->first();
                if($teacher_info){
                    $teacher_info->subject_id = $request->subject_id;
                    $teacher_info->is_counsellor = $request->is_counsellor ?? 0;
                    $teacher_info->save();
                }else{
                    $teacher_info = new TeacherInfo();
                    $teacher_info->user_id = $user->id;
                    $teacher_info->subject_id = $request->subject_id;
                    $teacher_info->is_counsellor = $request->is_counsellor ?? 0;
                    $teacher_info->save();
                }

                DB::commit();
                return send_msg("Teacher Updated Successfully!", true, 200);
    
            } catch (\Throwable $th) {
                DB::rollback();
                return send_msg("Something went wrong!", false, 200);
            }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        if(is_null($this->user) || !$this->user->can('teacher.delete')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $user = User::find($id);
        $user->delete();
        $teacher_info = TeacherInfo::where('user_id',$user->id)->first();
        if($teacher_info){
            $teacher_info->delete();
        }
        if($user->image != 'img/users/user.jpg'){
            if(env('APP_ENV') == 'production'){
                @unlink('public/'.$user->image);
            }else{
                @unlink($user->image);
            }
        }
        return send_msg('Delete Success', true, 200);
    }

    public function multipleDelete()
    {
        if(is_null($this->user) || !$this->user->can('teacher.delete')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $users = User::whereIn('id',request('ids'))->get();
        foreach ($users as $user) {
            $user->delete();
            if($user->image != 'img/users/user.jpg'){
                if(env('APP_ENV') == 'production'){
                    @unlink('public/'.$user->image);
                }else{
                    @unlink($user->image);
                }
            }
            $teacher_info = TeacherInfo::where('user_id',$user->id)->first();
            if($teacher_info){
                $teacher_info->delete();
            }
        }
        return send_msg('Delete Success', true, 200);

    }
   
}
