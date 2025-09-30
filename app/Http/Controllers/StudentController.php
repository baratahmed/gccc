<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\StudentInfo;
use App\Models\StudentSections;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class StudentController extends Controller
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
        if(is_null($this->user) || !$this->user->can('student.read')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $perPage = request('perPage') ?: 100;
        $users = User::query()
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Student');
                })
                ->when(request('search'), function($q, $search){
                    return $q->where('name','LIKE','%'.$search.'%')
                             ->orWhere('roll_no','LIKE','%'.$search.'%')
                             ->orWhere('phone','LIKE','%'.$search.'%')
                             ->orWhere('email','LIKE','%'.$search.'%');
                })
                ->orderBy('id','desc')
                ->paginate($perPage);
                
        return StudentResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        if(is_null($this->user) || !$this->user->can('student.create')){
            return send_msg('Unauthorized Access', false, 403);
        }

        if($request->session_id == '999'){
            return send_msg('Please select a Session', false, 200);
        }

        if($request->group_id == '999'){
            return send_msg('Please select a Group', false, 200);
        }

        if($request->section_ids == 'null'){
            return send_msg('Please select a Section', false, 200);
        }

        if(User::where('roll_no',$request->roll_no)->first()){
            return send_msg('Student ALready Exists!', false, 200);
        }

        $request->validate([
            'name' => 'required|max:50',
            'roll_no' => 'required|min:10|max:10',
            'email' => 'email:rfc|max:50|unique:users',
            'phone' => 'required|max:20',
            'image' => 'nullable|image|mimes:png,jpeg,jpg',
        ]);

        DB::beginTransaction();
        try {
                $user = new User();
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->roll_no = $request->roll_no;
                $user->email = $request->email; 
                $user->gender = $request->gender;                
                $user->religion = $request->religion;    
                $user->added_by = auth()->id(); 
                $user->password = bcrypt('password');
                $user->is_verified = $request->status ?? 0;
                $save_url = null;
                if($request->hasFile('image')){
                    $name_gen = 'STUDENT_'.hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($request->file('image'));
                    $img->save(base_path('public/img/users/'.$name_gen));
                    $save_url = 'img/users/'.$name_gen;
                }
                $user->image = $save_url ?? "img/users/user.jpg";
                $user->save();
                $user->assignRole('Student');  
                
                $std_info = new StudentInfo();
                $std_info->user_id = $user->id;
                $std_info->name_bn = $request->name_bn;
                $std_info->f_name = $request->f_name;
                $std_info->m_name = $request->m_name;
                $std_info->adm_roll_no = $request->adm_roll_no;
                $std_info->session_id = $request->session_id;
                $std_info->group_id = $request->group_id;   
                $std_info->save();

                foreach ($request->section_ids as $key => $scetion_id) {
                    $data = new StudentSections();
                    $data->user_id = $user->id;
                    $data->section_id = $scetion_id;
                    $data->save();
                }

                DB::commit();
                return send_msg("Student Created Successfully!", true, 200);
    
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
        if(is_null($this->user) || !$this->user->can('student.read')){
            return send_msg('Unauthorized Access', false, 403);
        }
        return new StudentResource(User::find($id));
    }

    public function update(Request $request)
    {
        // return $request->all();
        if(is_null($this->user) || !$this->user->can('student.update')){
            return send_msg('Unauthorized Access', false, 403);
        }  

        if($request->session_id == '999'){
            return send_msg('Please select a Session', false, 200);
        }
        
        if($request->group_id == '999'){
            return send_msg('Please select a Group', false, 200);
        }

        if($request->section_ids == 'null'){
            return send_msg('Please select a Section', false, 200);
        }         

        $request->validate([
            'id' => 'required',
            'name' => 'required|max:50',
            'roll_no' => 'required|min:10|max:10',
            'email' => 'email:rfc|max:50|unique:users,email,'.$request->id,
            'phone' => 'required|max:20',
            'image' => 'nullable|image|mimes:png,jpeg,jpg', 
        ]);

        DB::beginTransaction();
        try {
                $user = User::find($request->id);
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->roll_no = $request->roll_no;
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
                    $name_gen = 'STUDENT_'.hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($request->file('image'));
                    $img->save(base_path('public/img/users/'.$name_gen));
                    $save_url = 'img/users/'.$name_gen;
                }
                if(isset($save_url)){
                    $user->image = $save_url;
                }                
                $user->save();
                
                $std_info = StudentInfo::where('user_id',$user->id)->first();
                $std_info->name_bn = $request->name_bn;
                $std_info->f_name = $request->f_name;
                $std_info->m_name = $request->m_name;
                $std_info->adm_roll_no = $request->adm_roll_no;
                $std_info->session_id = $request->session_id;
                $std_info->group_id = $request->group_id;
                $std_info->save();

                StudentSections::where('user_id',$user->id)->delete();

                foreach ($request->section_ids as $key => $scetion_id) {
                    $data = new StudentSections();
                    $data->user_id = $user->id;
                    $data->section_id = $scetion_id;
                    $data->save();
                }

                DB::commit();
                return send_msg("Student Updated Successfully!", true, 200);
    
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
        if(is_null($this->user) || !$this->user->can('student.delete')){
            return send_msg('Unauthorized Access', false, 403);
        }
        $user = User::find($id);
        $user->delete();
        $student_info = StudentInfo::where('user_id',$user->id)->first();
        if($student_info){
            $student_info->delete();
        }
        StudentSections::where('user_id',$user->id)->delete();

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
        if(is_null($this->user) || !$this->user->can('student.delete')){
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
            $student_info = StudentInfo::where('user_id',$user->id)->first();
            if($student_info){
                $student_info->delete();
            }
            StudentSections::where('user_id',$user->id)->delete();

        }
        return send_msg('Delete Success', true, 200);

    }


   
}
