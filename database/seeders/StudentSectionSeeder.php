<?php

namespace Database\Seeders;

use App\Models\StudentSections;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // FOR 1ST YAER 

        $sci_students = User::query()
                ->whereHas('student_info', function ($q) {
                    $q->where('group_id', 1);
                    $q->where('session_id', 2);
                })
                ->pluck('id');

        foreach ($sci_students as $index => $student_id) {
            $std_section = new StudentSections();
            $std_section->user_id = $student_id;

            if ($index < 235) {
                $std_section->section_id = 1;
            }elseif($index < 470) {
                $std_section->section_id = 2;
            }else {
                $std_section->section_id = 3;
            }
            $std_section->save();
        }

        foreach ($sci_students as $index => $student_id) {
            $std_section = new StudentSections();
            $std_section->user_id = $student_id;

            if ($index < 175) {
                $std_section->section_id = 4;
            } elseif ($index < 350) {
                $std_section->section_id = 5;
            } elseif ($index < 525) {
                $std_section->section_id = 6;
            } else {
                $std_section->section_id = 7;
            }
            $std_section->save();
        }

        foreach ($sci_students as $index => $student_id) {
            $std_section = new StudentSections();
            $std_section->user_id = $student_id;

            if ($index < 117) {
                $std_section->section_id = 8;
            } elseif ($index < 235) {
                $std_section->section_id = 9;
            } elseif ($index < 352) {
                $std_section->section_id = 10;
            } elseif ($index < 470) {
                $std_section->section_id = 11;
            } elseif ($index < 588) {
                $std_section->section_id = 12;
            }else {
                $std_section->section_id = 13;
            }
            $std_section->save();
        }


        $bs_students = User::query()
                ->whereHas('student_info', function ($q) {
                    $q->where('group_id', 2);
                    $q->where('session_id', 2);
                })
                ->pluck('id');
        foreach ($bs_students as $index => $student_id) {
            $std_section = new StudentSections();
            $std_section->user_id = $student_id;
            if ($index < 368) {
                $std_section->section_id = 24;
            }else {
                $std_section->section_id = 25;
            }
            $std_section->save();
        }

        foreach ($bs_students as $index => $student_id) {
            $std_section = new StudentSections();
            $std_section->user_id = $student_id;

            if ($index < 180) {
                $std_section->section_id = 26;
            } elseif ($index < 368) {
                $std_section->section_id = 27;
            } elseif($index < 548) {
                $std_section->section_id = 28;
            }else{
                $std_section->section_id = 29;
            }
            $std_section->save();
        }


        $hum_students = User::query()
                ->whereHas('student_info', function ($q) {
                    $q->where('group_id', 3);
                    $q->where('session_id', 2);
                })
                ->pluck('id');

        foreach ($hum_students as $index => $student_id) {
            $std_section = new StudentSections();
            $std_section->user_id = $student_id;
            if ($index < 350) {
                $std_section->section_id = 34;
            }else {
                $std_section->section_id = 35;
            }
            $std_section->save();
        }

        foreach ($hum_students as $index => $student_id) {
            $std_section = new StudentSections();
            $std_section->user_id = $student_id;

            if ($index < 180) {
                $std_section->section_id = 36;
            } elseif ($index < 350) {
                $std_section->section_id = 37;
            } elseif($index < 530) {
                $std_section->section_id = 38;
            }else{
                $std_section->section_id = 39;
            }
            $std_section->save();
        }







       

        // FOR 2ND YEAR
        // $sci_students = User::query()
        //         ->whereHas('student_info', function ($q) {
        //             $q->where('group_id', 1);
        //             $q->where('session_id', 1);
        //         })
        //         ->pluck('id');
        // foreach ($sci_students as $index => $student_id) {
        //     $std_section = new StudentSections();
        //     $std_section->user_id = $student_id;

        //     if ($index < 300) {
        //         $std_section->section_id = 14;
        //     }else {
        //         $std_section->section_id = 15;
        //     }
        //     $std_section->save();
        // }

        // foreach ($sci_students as $index => $student_id) {
        //     $std_section = new StudentSections();
        //     $std_section->user_id = $student_id;

        //     if ($index < 150) {
        //         $std_section->section_id = 16;
        //     } elseif ($index < 300) {
        //         $std_section->section_id = 17;
        //     } elseif ($index < 450) {
        //         $std_section->section_id = 18;
        //     } else {
        //         $std_section->section_id = 19;
        //     }
        //     $std_section->save();
        // }



        // $bs_students = User::query()
        //         ->whereHas('student_info', function ($q) {
        //             $q->where('group_id', 2);
        //             $q->where('session_id', 1);
        //         })
        //         ->pluck('id');
        // foreach ($bs_students as $index => $student_id) {
        //     $std_section = new StudentSections();
        //     $std_section->user_id = $student_id;
        //     if ($index < 368) {
        //         $std_section->section_id = 24;
        //     }else {
        //         $std_section->section_id = 25;
        //     }
        //     $std_section->save();
        // }

        // foreach ($bs_students as $index => $student_id) {
        //     $std_section = new StudentSections();
        //     $std_section->user_id = $student_id;

        //     if ($index < 180) {
        //         $std_section->section_id = 26;
        //     } elseif ($index < 368) {
        //         $std_section->section_id = 27;
        //     } elseif($index < 548) {
        //         $std_section->section_id = 28;
        //     }else{
        //         $std_section->section_id = 29;
        //     }
        //     $std_section->save();
        // }


        // $hum_students = User::query()
        //         ->whereHas('student_info', function ($q) {
        //             $q->where('group_id', 3);
        //             $q->where('session_id', 1);
        //         })
        //         ->pluck('id');

        // foreach ($hum_students as $index => $student_id) {
        //     $std_section = new StudentSections();
        //     $std_section->user_id = $student_id;
        //     if ($index < 350) {
        //         $std_section->section_id = 34;
        //     }else {
        //         $std_section->section_id = 35;
        //     }
        //     $std_section->save();
        // }

        // foreach ($hum_students as $index => $student_id) {
        //     $std_section = new StudentSections();
        //     $std_section->user_id = $student_id;

        //     if ($index < 180) {
        //         $std_section->section_id = 36;
        //     } elseif ($index < 350) {
        //         $std_section->section_id = 37;
        //     } elseif($index < 530) {
        //         $std_section->section_id = 38;
        //     }else{
        //         $std_section->section_id = 39;
        //     }
        //     $std_section->save();
        // }


    }
}
