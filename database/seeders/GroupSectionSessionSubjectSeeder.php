<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Group;
use App\Models\Section;
use App\Models\Session;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSectionSessionSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Session::insert([
            [
                "id" => 1,
                "name" => "2024-2025",
                "type" => "2ND_YEAR",
            ],
        ]);
        
        Group::insert([
            [
                "id" => 1,
                "name" => "SCIENCE",
                "short_name" => "SCI",
            ],
            [
                "id" => 2,
                "name" => "BUSINESS STUDIES",
                "short_name" => "B. STU",
            ],
            [
                "id" => 3,
                "name" => "HUMANITIES",
                "short_name" => "HUM",
            ],
        ]);


        Section::insert([

            // FOR 1ST YEAR

            // SCI
            [
                "id" => 1,
                "group_id" => 1,
                "name" => "S1",
                "shift" => "DAY",
                "type" => "THEORY",
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 2,
                "group_id" => 1,
                "name" => "S2",
                "shift" => "DAY",
                "type" => "THEORY",
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 3,
                "group_id" => 1,
                "name" => "S3",
                "shift" => "DAY",
                "type" => "THEORY",
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 4,
                "group_id" => 1,
                "name" => "S(A1)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "1ST_YEAR_ICT",
            ],
            [
                "id" => 5,
                "group_id" => 1,
                "name" => "SA(2)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "1ST_YEAR_ICT",
            ],
            [
                "id" => 6,
                "group_id" => 1,
                "name" => "S(B1)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "1ST_YEAR_ICT",
            ],
            [
                "id" => 7,
                "group_id" => 1,
                "name" => "S(B2)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "1ST_YEAR_ICT",
            ],
            [
                "id" => 8,
                "group_id" => 1,
                "name" => "S1(A)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 9,
                "group_id" => 1,
                "name" => "S1(B)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 10,
                "group_id" => 1,
                "name" => "S2(A)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 11,
                "group_id" => 1,
                "name" => "S2(B)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 12,
                "group_id" => 1,
                "name" => "S3(A)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 13,
                "group_id" => 1,
                "name" => "S3(B)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 14,
                "group_id" => 1,
                "name" => "S(A)",
                "shift" => "DAY",
                "type" => "THEORY",
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 15,
                "group_id" => 1,
                "name" => "S(B)",
                "shift" => "DAY",
                "type" => "THEORY",
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 16,
                "group_id" => 1,
                "name" => "S(A1)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 17,
                "group_id" => 1,
                "name" => "S(A2)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 18,
                "group_id" => 1,
                "name" => "S(B1)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 19,
                "group_id" => 1,
                "name" => "S(B2)",
                "shift" => "DAY",
                "type" => "LAB",
                "for" => "2ND_YEAR",
            ],
            


            // B.Stu

            [
                "id" => 20,
                "group_id" => 2,
                "name" => "BS1",
                "shift" => "DAY",
                "type" => 'BOTH',
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 21,
                "group_id" => 2,
                "name" => "BS2",
                "shift" => "DAY",
                "type" => 'BOTH',
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 22,
                "group_id" => 2,
                "name" => "BS1",
                "shift" => "EVENING",
                "type" => 'BOTH',
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 23,
                "group_id" => 2,
                "name" => "BS2",
                "shift" => "EVENING",
                "type" => 'BOTH',
                "for" => "1ST_YEAR",
            ],

            [
                "id" => 24,
                "group_id" => 2,
                "name" => "BS",
                "shift" => "DAY",
                "type" => 'THEORY',
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 25,
                "group_id" => 2,
                "name" => "BS",
                "shift" => "EVENING",
                "type" => 'THEORY',
                "for" => "2ND_YEAR",
            ],

            [
                "id" => 26,
                "group_id" => 2,
                "name" => "BS1",
                "shift" => "DAY",
                "type" => 'LAB',
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 27,
                "group_id" => 2,
                "name" => "BS2",
                "shift" => "DAY",
                "type" => 'LAB',
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 28,
                "group_id" => 2,
                "name" => "BS1",
                "shift" => "EVENING",
                "type" => 'LAB',
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 29,
                "group_id" => 2,
                "name" => "BS2",
                "shift" => "EVENING",
                "type" => 'LAB',
                "for" => "2ND_YEAR",
            ],


            // Hum
            [
                "id" => 30,
                "group_id" => 3,
                "name" => "H1",
                "shift" => "DAY",
                "type" => 'BOTH',
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 31,
                "group_id" => 3,
                "name" => "H2",
                "shift" => "DAY",
                "type" => 'BOTH',
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 32,
                "group_id" => 3,
                "name" => "H1",
                "shift" => "EVENING",
                "type" => 'BOTH',
                "for" => "1ST_YEAR",
            ],
            [
                "id" => 33,
                "group_id" => 3,
                "name" => "H2",
                "shift" => "EVENING",
                "type" => 'BOTH',
                "for" => "1ST_YEAR",
            ],

            [
                "id" => 34,
                "group_id" => 3,
                "name" => "HUM",
                "shift" => "DAY",
                "type" => 'THEORY',
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 35,
                "group_id" => 3,
                "name" => "HUM",
                "shift" => "EVENING",
                "type" => 'THEORY',
                "for" => "2ND_YEAR",
            ],

            [
                "id" => 36,
                "group_id" => 3,
                "name" => "H1",
                "shift" => "DAY",
                "type" => 'LAB',
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 37,
                "group_id" => 3,
                "name" => "H2",
                "shift" => "DAY",
                "type" => 'LAB',
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 38,
                "group_id" => 3,
                "name" => "H1",
                "shift" => "EVENING",
                "type" => 'LAB',
                "for" => "2ND_YEAR",
            ],
            [
                "id" => 39,
                "group_id" => 3,
                "name" => "H2",
                "shift" => "EVENING",
                "type" => 'LAB',
                "for" => "2ND_YEAR",
            ],
        ]);

        Subject::insert([
            [
                "id" => 1,
                "group_id" => null,
                "name" => "BANGLA",
                "short_name" => "BANG",
                "code" => "101",
                "has_lab" => false,
            ],
            [
                "id" => 2,
                "group_id" => null,
                "name" => "ENGLISH",
                "short_name" => "ENG",
                "code" => "107",
                "has_lab" => false,
            ],
            [
                "id" => 3,
                "group_id" => null,
                "name" => "ICT",
                "short_name" => "ICT",
                "code" => "275",
                "has_lab" => true,
            ],

            // Science
            [
                "id" => 4,
                "group_id" => 1,
                "name" => "PHYSICS",
                "short_name" => "PHY",
                "code" => "174",
                "has_lab" => true,
            ],          
            [
                "id" => 5,
                "group_id" => 1,
                "name" => "CHEMISTRY",
                "short_name" => "CHEM",
                "code" => "176",
                "has_lab" => true,
            ],           
            [
                "id" => 6,
                "group_id" => 1,
                "name" => "HIGHER MATH",
                "short_name" => "MATH",
                "code" => "265",
                "has_lab" => true,
            ],          
            [
                "id" => 7,
                "group_id" => 1,
                "name" => "BIOLOGY",
                "short_name" => "BIO",
                "code" => "178",
                "has_lab" => true,
            ],


            // Business Studies
            [
                "id" => 8,
                "group_id" => 2,
                "name" => "ACCOUNTING",
                "short_name" => "ACC",
                "code" => "253",
                "has_lab" => false,
            ],
            [
                "id" => 9,
                "group_id" => 2,
                "name" => "Business Organization and Management",
                "short_name" => "B.ORG",
                "code" => "277",
                "has_lab" => false,
            ],
            [
                "id" => 10,
                "group_id" => 2,
                "name" => "Finance, Banking and Insurance",
                "short_name" => "FBI",
                "code" => "292",
                "has_lab" => false,
            ],
            [
                "id" => 11,
                "group_id" => 4,
                "name" => "ECONOMICS",
                "short_name" => "ECO",
                "code" => "109",
                "has_lab" => false,
            ],

            // Humanities

            [
                "id" => 12,
                "group_id" => 3,
                "name" => "CIVIC & GOOD GOVERNANCE",
                "short_name" => "CIV",
                "code" => "269",
                "has_lab" => false,
            ],           
            [
                "id" => 13,
                "group_id" => 3,
                "name" => "ISLAMIC HISTORY & CULTURE",
                "short_name" => "IHC",
                "code" => "267",
                "has_lab" => false,
            ],
            [
                "id" => 14,
                "group_id" => 3,
                "name" => "LOGIC",
                "short_name" => "LOG",
                "code" => "121",
                "has_lab" => false,
            ],
            [
                "id" => 15,
                "group_id" => 3,
                "name" => "STUDIES OF ISLAM",
                "short_name" => "I. STU",
                "code" => "249",
                "has_lab" => false,
            ],
            [
                "id" => 16,
                "group_id" => 3,
                "name" => "PSYCHOLOGY",
                "short_name" => "PSY",
                "code" => "123",
                "has_lab" => false,
            ],
        ]);
    }
}
