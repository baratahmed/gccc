<?php

namespace Database\Seeders;

use App\Models\StudentSubjects;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\DB;

class HumStdSubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        LazyCollection::make(function(){
            $handle = fopen(public_path('sql_files/humanities_students_subjects.csv'),'r');
            while(($line = fgetcsv($handle,4096)) != false){
                $dataString = implode(', ',$line);
                $row = explode(',',$dataString);
                yield $row;
            }
            fclose($handle);
        })
        ->chunk(100)
        ->each(function(LazyCollection $chunk){
            $chunk->each(function($row){
                $student = User::where('roll_no','like','%'.$row[0])->first();
                if($student){
                    $std_sub = new StudentSubjects();
                    $std_sub->student_id = $student->id; 
                    $std_sub->first_subject_id = Subject::where('code',trim($row[1]))->first()->id; 
                    $std_sub->second_subject_id = Subject::where('code',trim($row[3]))->first()->id; 
                    $std_sub->third_subject_id = Subject::where('code',trim($row[5]))->first()->id; 
                    $std_sub->fourth_subject_id = Subject::where('code',trim($row[7]))->first()->id; 
                    $std_sub->save();
                }
            });
        });

    }
}
