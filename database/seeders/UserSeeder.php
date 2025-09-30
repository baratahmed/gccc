<?php

namespace Database\Seeders;

use App\Models\StudentInfo;
use App\Models\StudentSections;
use App\Models\TeacherInfo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // $user = new User();
        // $user->name = 'Admin';
        // $user->phone = '01812521337';
        // $user->email = 'admin@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password2');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Admin');

        // $user = new User();
        // $user->name = 'Md. Mustafa Reza Hasan';
        // $user->phone = '01521400573';
        // $user->email = 'mustafareza003@gmail.com';
        // $user->image = 'img/users/TEACHER_1841759413325211.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 3;
        // $teacher_info->is_counsellor = 1;
        // $teacher_info->save();


        // $user = new User();
        // $user->name = 'Kazi Jamal Uddin';
        // $user->phone = '01819630166';
        // $user->email = 'jamal_citycollege@yahoo.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 3;
        // $teacher_info->is_counsellor = 1;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Md. Abdullah';
        // $user->phone = '01822234105';
        // $user->email = 'md.abdullarone@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 3;
        // $teacher_info->is_counsellor = 1;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Shahedul Karim';
        // $user->phone = '01812946906';
        // $user->email = 'engrshahedulkarim@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // // $2y$10$0DdNatybNx4LZ9Dxlx843uuLwTlaHTClC6fyCiLYcPoZBb0bPcphS
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 3;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // // Other Teacher

        // $user = new User();
        // $user->name = 'Bangla Teacher';
        // $user->phone = '012345001';
        // $user->email = 'bangla@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 1;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'English Teacher';
        // $user->phone = '012345002';
        // $user->email = 'english@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 2;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Physics Teacher';
        // $user->phone = '012345004';
        // $user->email = 'physics@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 4;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Chemistry Teacher';
        // $user->phone = '012345005';
        // $user->email = 'chemistry@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 5;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Math Teacher';
        // $user->phone = '012345006';
        // $user->email = 'math@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 6;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Biology Teacher';
        // $user->phone = '012345007';
        // $user->email = 'biology@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 7;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();



        // $user = new User();
        // $user->name = 'Accounting Teacher';
        // $user->phone = '012345008';
        // $user->email = 'accounting@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 8;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'BORG Teacher';
        // $user->phone = '012345009';
        // $user->email = 'borg@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 9;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Finance Teacher';
        // $user->phone = '012345010';
        // $user->email = 'finance@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 10;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Economics Teacher';
        // $user->phone = '012345011';
        // $user->email = 'economics@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 11;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Civics Teacher';
        // $user->phone = '012345012';
        // $user->email = 'civic@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 12;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Islamic History Teacher';
        // $user->phone = '012345013';
        // $user->email = 'ihc@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 13;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Logic Teacher';
        // $user->phone = '012345014';
        // $user->email = 'logic@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 14;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Studies of Islam Teacher';
        // $user->phone = '012345015';
        // $user->email = 'stuislam@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 15;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        // $user = new User();
        // $user->name = 'Psychology Teacher';
        // $user->phone = '012345016';
        // $user->email = 'phychology@gmail.com';
        // $user->image = 'img/users/user.jpg';
        // $user->password = bcrypt('password');
        // $user->gender = "MALE";
        // $user->religion = "ISLAM";
        // $user->is_verified = true;
        // $user->save();
        // $user->assignRole('Teacher');

        // $teacher_info = new TeacherInfo();
        // $teacher_info->user_id = $user->id;
        // $teacher_info->subject_id = 16;
        // $teacher_info->is_counsellor = 0;
        // $teacher_info->save();

        
        // First Year Students Seeder

        DB::disableQueryLog();
        LazyCollection::make(function(){
            $handle = fopen(public_path('sql_files/SCIENCE.csv'),'r');
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
                $user = new User();
                $user->name = trim($row[4]);
                $user->phone = trim($row[7]);
                $user->roll_no = trim($row[0]);
                $user->password = bcrypt(trim($row[0]));
                // $user->password = bcrypt('password');
                $user->is_verified = 1;
                $user->save();
                $user->assignRole('Student');

                $std_info = new StudentInfo();
                $std_info->user_id = $user->id;
                $std_info->f_name = trim($row[5]);
                $std_info->m_name = trim($row[6]);
                $std_info->adm_roll_no = trim($row[2]);
                $std_info->session_id = 2;
                $std_info->group_id = 1;
                $std_info->save();
            });
        });

        DB::disableQueryLog();
        LazyCollection::make(function(){
            $handle = fopen(public_path('sql_files/BUSINESS_STUDIES.csv'),'r');
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
               $user = new User();
                $user->name = trim($row[4]);
                $user->phone = trim($row[7]);
                $user->roll_no = trim($row[0]);
                $user->password = bcrypt(trim($row[0]));
                // $user->password = bcrypt('password');
                $user->is_verified = 1;
                $user->save();
                $user->assignRole('Student');

                $std_info = new StudentInfo();
                $std_info->user_id = $user->id;
                $std_info->f_name = trim($row[5]);
                $std_info->m_name = trim($row[6]);
                $std_info->adm_roll_no = trim($row[2]);
                $std_info->session_id = 2;
                $std_info->group_id = 2;
                $std_info->save();
            });
        });


        DB::disableQueryLog();
        LazyCollection::make(function(){
            $handle = fopen(public_path('sql_files/HUMANITIES.csv'),'r');
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
                $user = new User();
                $user->name = trim($row[4]);
                $user->phone = trim($row[7]);
                $user->roll_no = trim($row[0]);
                $user->password = bcrypt(trim($row[0]));
                // $user->password = bcrypt('password');
                $user->is_verified = 1;
                $user->save();
                $user->assignRole('Student');

                $std_info = new StudentInfo();
                $std_info->user_id = $user->id;
                $std_info->f_name = trim($row[5]);
                $std_info->m_name = trim($row[6]);
                $std_info->adm_roll_no = trim($row[2]);
                $std_info->session_id = 2;
                $std_info->group_id = 3;
                $std_info->save();
            });
        });



        // Second Year Students Seeder

        // DB::disableQueryLog();
        // LazyCollection::make(function(){
        //     $handle = fopen(public_path('sql_files/SA.csv'),'r');
        //     while(($line = fgetcsv($handle,4096)) != false){
        //         $dataString = implode(', ',$line);
        //         $row = explode(',',$dataString);
        //         yield $row;
        //     }
        //     fclose($handle);
        // })
        // ->chunk(100)
        // ->each(function(LazyCollection $chunk){
        //     $chunk->each(function($row){
        //         $user = new User();
        //         $user->name = trim($row[4]);
        //         $user->phone = trim($row[7]);
        //         $user->roll_no = trim($row[1]);
        //         $user->password = bcrypt(trim($row[1]));
        //         // $user->password = bcrypt('password');
        //         $user->is_verified = 1;
        //         $user->save();
        //         $user->assignRole('Student');

        //         $std_info = new StudentInfo();
        //         $std_info->user_id = $user->id;
        //         $std_info->f_name = trim($row[5]);
        //         $std_info->m_name = trim($row[6]);
        //         $std_info->adm_roll_no = trim($row[2]);
        //         $std_info->session_id = 1;
        //         $std_info->group_id = 1;
        //         $std_info->save();
        //     });
        // });


        // DB::disableQueryLog();
        // LazyCollection::make(function(){
        //     $handle = fopen(public_path('sql_files/SB.csv'),'r');
        //     while(($line = fgetcsv($handle,4096)) != false){
        //         $dataString = implode(', ',$line);
        //         $row = explode(',',$dataString);
        //         yield $row;
        //     }
        //     fclose($handle);
        // })
        // ->chunk(100)
        // ->each(function(LazyCollection $chunk){
        //     $chunk->each(function($row){
        //         $user = new User();
        //         $user->name = trim($row[4]);
        //         $user->phone = trim($row[7]);
        //         $user->roll_no = trim($row[1]);
        //         $user->password = bcrypt(trim($row[1]));
        //         // $user->password = bcrypt('password');
        //         $user->is_verified = 1;
        //         $user->save();
        //         $user->assignRole('Student');

        //         $std_info = new StudentInfo();
        //         $std_info->user_id = $user->id;
        //         $std_info->f_name = trim($row[5]);
        //         $std_info->m_name = trim($row[6]);
        //         $std_info->adm_roll_no = trim($row[2]);
        //         $std_info->session_id = 1;
        //         $std_info->group_id = 1;
        //         $std_info->save();
        //     });
        // });


        // DB::disableQueryLog();
        // LazyCollection::make(function(){
        //     $handle = fopen(public_path('sql_files/BS_DAY.csv'),'r');
        //     while(($line = fgetcsv($handle,4096)) != false){
        //         $dataString = implode(', ',$line);
        //         $row = explode(',',$dataString);
        //         yield $row;
        //     }
        //     fclose($handle);
        // })
        // ->chunk(100)
        // ->each(function(LazyCollection $chunk){
        //     $chunk->each(function($row){
        //         $user = new User();
        //         $user->name = trim($row[4]);
        //         $user->phone = trim($row[7]);
        //         $user->roll_no = trim($row[1]);
        //         $user->password = bcrypt(trim($row[1]));
        //         // $user->password = bcrypt('password');
        //         $user->is_verified = 1;
        //         $user->save();
        //         $user->assignRole('Student');

        //         $std_info = new StudentInfo();
        //         $std_info->user_id = $user->id;
        //         $std_info->f_name = trim($row[5]);
        //         $std_info->m_name = trim($row[6]);
        //         $std_info->adm_roll_no = trim($row[2]);
        //         $std_info->session_id = 1;
        //         $std_info->group_id = 2;
        //         $std_info->save();
        //     });
        // });


        // DB::disableQueryLog();
        // LazyCollection::make(function(){
        //     $handle = fopen(public_path('sql_files/BS_EVENING.csv'),'r');
        //     while(($line = fgetcsv($handle,4096)) != false){
        //         $dataString = implode(', ',$line);
        //         $row = explode(',',$dataString);
        //         yield $row;
        //     }
        //     fclose($handle);
        // })
        // ->chunk(100)
        // ->each(function(LazyCollection $chunk){
        //     $chunk->each(function($row){
        //         $user = new User();
        //         $user->name = trim($row[4]);
        //         $user->phone = trim($row[7]);
        //         $user->roll_no = trim($row[1]);
        //         $user->password = bcrypt(trim($row[1]));
        //         // $user->password = bcrypt('password');
        //         $user->is_verified = 1;
        //         $user->save();
        //         $user->assignRole('Student');

        //         $std_info = new StudentInfo();
        //         $std_info->user_id = $user->id;
        //         $std_info->f_name = trim($row[5]);
        //         $std_info->m_name = trim($row[6]);
        //         $std_info->adm_roll_no = trim($row[2]);
        //         $std_info->session_id = 1;
        //         $std_info->group_id = 2;
        //         $std_info->save();
        //     });
        // });


        // // Humanities

        // DB::disableQueryLog();
        // LazyCollection::make(function(){
        //     $handle = fopen(public_path('sql_files/H_DAY.csv'),'r');
        //     while(($line = fgetcsv($handle,4096)) != false){
        //         $dataString = implode(', ',$line);
        //         $row = explode(',',$dataString);
        //         yield $row;
        //     }
        //     fclose($handle);
        // })
        // ->chunk(100)
        // ->each(function(LazyCollection $chunk){
        //     $chunk->each(function($row){
        //         $user = new User();
        //         $user->name = trim($row[4]);
        //         $user->phone = trim($row[7]);
        //         $user->roll_no = trim($row[1]);
        //         $user->password = bcrypt(trim($row[1]));
        //         // $user->password = bcrypt('password');
        //         $user->is_verified = 1;
        //         $user->save();
        //         $user->assignRole('Student');

        //         $std_info = new StudentInfo();
        //         $std_info->user_id = $user->id;
        //         $std_info->f_name = trim($row[5]);
        //         $std_info->m_name = trim($row[6]);
        //         $std_info->adm_roll_no = trim($row[2]);
        //         $std_info->session_id = 1;
        //         $std_info->group_id = 3;
        //         $std_info->save();
        //     });
        // });


        // DB::disableQueryLog();
        // LazyCollection::make(function(){
        //     $handle = fopen(public_path('sql_files/H_EVENING.csv'),'r');
        //     while(($line = fgetcsv($handle,4096)) != false){
        //         $dataString = implode(', ',$line);
        //         $row = explode(',',$dataString);
        //         yield $row;
        //     }
        //     fclose($handle);
        // })
        // ->chunk(100)
        // ->each(function(LazyCollection $chunk){
        //     $chunk->each(function($row){
        //         $user = new User();
        //         $user->name = trim($row[4]);
        //         $user->phone = trim($row[7]);
        //         $user->roll_no = trim($row[1]);
        //         $user->password = bcrypt(trim($row[1]));
        //         // $user->password = bcrypt('password');
        //         $user->is_verified = 1;
        //         $user->save();
        //         $user->assignRole('Student');

        //         $std_info = new StudentInfo();
        //         $std_info->user_id = $user->id;
        //         $std_info->f_name = trim($row[5]);
        //         $std_info->m_name = trim($row[6]);
        //         $std_info->adm_roll_no = trim($row[2]);
        //         $std_info->session_id = 1;
        //         $std_info->group_id = 3;
        //         $std_info->save();
        //     });
        // });

    }
}