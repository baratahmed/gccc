<?php

namespace App\Http\Controllers;

use App\Models\StudentSections;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(){
        
        // $sci_students = User::query()
        //         ->whereHas('student_info', function ($q) {
        //             $q->where('group_id', 1);
        //         })->get();

        // foreach ($sci_students as $key => $value) {
        //     $std_section = new StudentSections();
        //     $std_section->user_id = $stdent_id;
        //     $std_section->section_id = 1;
        //     $std_section->save();
        // }
    }
}
