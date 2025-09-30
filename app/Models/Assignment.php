<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    public function student()
    {
        return $this->belongsTo(User::class,'student_id','id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class,'topic_id','id');
    }

    public function files()
    {
        return $this->hasMany(File::class,'fileable_id');
    }
}
