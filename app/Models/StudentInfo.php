<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
    use HasFactory;

    public function session(){
        return $this->belongsTo(Session::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function section(){
        return $this->belongsTo(Section::class);
    }
}
