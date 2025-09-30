<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Http\Resources\TopicResource;
use App\Models\Attendance;
use App\Models\Session;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SessionController extends Controller
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
        $sessions = Session::query()
                ->orderBy('id','desc')
                ->get();
                
        return SessionResource::collection($sessions);
    }

   
    public function store(Request $request)
    {

        $session = new Session();
        $session->name = $request->name;
        $session->type = $request->type;
        $session->save();

        return send_msg("Session Created Successfully!", true, 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new SessionResource(Session::find($id));
    }

    // public function lastSession()
    // {
    //     return new SessionResource(Session::orderBy('id','desc')->first());
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $session = Session::find($request->id);
        $session->name = $request->name;
        $session->type = $request->type;
        $session->save();

        return send_msg("Session Updated Successfully!", true, 200);
    }

    public function destroy(Request $request, $id)
    {
        $session = Session::find($id);
        $session->delete();       
        return send_msg('Delete Success', true, 200);
    }

}
