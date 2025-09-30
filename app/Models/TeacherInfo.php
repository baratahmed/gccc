<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherInfo extends Model
{
    use HasFactory;

    protected $with = ['teacher:id,name,phone'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function teacher(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }
    
}
