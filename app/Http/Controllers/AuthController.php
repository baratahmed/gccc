<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AuthController extends Controller
{
    public function login(LoginRequest $request){

        if(!isset($request->user_type)){
            /*For Admin Start */
            $user = User::where('phone',$request->phone)->first();
            if( $user && $user->role()->name == 'Admin'){
                if (!Hash::check($request->password, $user->password)) {
                    throw ValidationException::withMessages([
                        'message' => ['The provided credentials are incorrect.'],
                    ]);
                }else{
                    return $this->makeToken($user);
                }
            }else{
                throw ValidationException::withMessages([
                    'message' => ['Please select a user type.'],
                ]);
            }
            /*For Admin End */
        }
        else{
            if($request->user_type != 'STUDENT'){
                $user = User::verifiedUser()->where('phone', $request->phone)->first();
                if (! $user || ! Hash::check($request->password, $user->password)) {
                    throw ValidationException::withMessages([
                        'message' => ['The provided credentials are incorrect.'],
                    ]);
                }
                return $this->makeToken($user);
            }else{
                // return $request->all();
                $user = User::verifiedUser()->where('roll_no', (string) $request->roll_no)->first();
                if (! $user || ! Hash::check($request->password, $user->password)) {
                    throw ValidationException::withMessages([
                        'message' => ['The provided credentials are incorrect.'],
                    ]);
                }
                return $this->makeToken($user);
            }

        }
        
    }

    public function makeToken($user){
        $token = $user->createToken('gccc')->plainTextToken;
        // return response()->json([
        //     'token' => $token,
        //     'user' => AuthResource::make($user),
        // ]);

        return (new AuthResource($user))
                ->additional(['meta' => [
                    'token' => $token,
                ]]);
    }

    public function logout(Request $request){
        try {
            $request->user()->tokens()->delete();
            return send_msg('Logout Success', true, 200);
        } catch (\Exception $e) {
            return send_msg($e->getMessage(), false, $e->getCode());
        }
    }

    public function user(Request $request){
        return AuthResource::make($request->user());
    }

    public function getInstantRolePermissions($user_id){
        $user = User::find($user_id);
        return [
            'role' => $user->role()->name,
            'permissions' => $this->getAllPermissions()->pluck('name'),
        ];
    }


    public function updateProfileImage(Request $request){
        if($request->hasFile('image')){
            if($request->user_id){
                $user = User::find($request->user_id);
            }else{
                send_msg('Something went wrong!',false, 200);
            }
            if($user->image != 'img/users/user.jpg'){
                if(env('APP_ENV') == 'production'){
                    @unlink('public/'.$user->image);
                }else{
                    @unlink($user->image);
                }
            }
            $name_gen = $user->id.hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
            $manager = new ImageManager(new Driver());
            $img = $manager->read($request->file('image'));
            $img->save(base_path('public/img/users/'.$name_gen));
            $save_url = 'img/users/'.$name_gen;
            $user->update([
                'image' => $save_url
            ]);
            return $save_url;
        }
    }

    public function updateProfile(Request $request){
        
        $validated = $request->validate([
            'user_id' => 'required',
            'name' => 'required',
            'email' => 'required|email:rfc|max:50|unique:users,email,'.$request->user_id,
            'phone' => 'required|max:20|unique:users,phone,'.$request->user_id,
        ]);

        $user = User::find($request->user_id);

        if($validated){
            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);        

            return send_msg('Profile Updated', true, 200);
    
        }else{
            return send_msg('Something Went Wrong!', false, 200);
        }

    }

    public function changePassword(Request $request){
        
        $validated = $request->validate([
            'user_id' => ['required'],
            'current_password' => ['required'],
            'password' => ['required',Password::defaults(),'confirmed'],
        ]);

        $user = User::find($request->user_id);

        if($validated && Hash::check($request->current_password, $user->password)){
            $user->update([
                'password' => bcrypt($validated['password']),
            ]);        

            return send_msg('Password Updated', true, 200);
    
        }else{
            return send_msg('Something Went Wrong!', false, 200);
        }

    }


    // public function storeAddress(Request $request){
    //     $request->user()->update([
    //         'division_id' => $request->division_id,
    //         'zila_id' => $request->zila_id,
    //         'upazila_id' => $request->upazila_id,
    //         'union_id' => $request->union_id,
    //         'address' => $request->address,
    //     ]);
    //     return send_msg('Address Saved', true, 201);
    // }

    // public function address(Request $request){
    //     $id = Auth::guard('user-api')->id();

    //     $data = User::where('id',$id)->select('division_id','district_id','address')
    //                     ->with(['division'=>function($q){
    //                         $q->select('id','name','charge');
    //                     },'district'=>function($q){
    //                         $q->select('id','name');
    //                     }])
    //                     ->first();
    //     return $data;
    // }
}
