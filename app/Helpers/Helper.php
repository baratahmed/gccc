<?php

function send_msg($msg,$status,$code){
    $res = [
        'status' => $status,
        'message' => $msg,
    ];

    return response()->json($res,$code);
}

function laravel_date($date){
    $date = preg_replace('/\(.*\)$/','',$date);
    return date('Y-m-d',strtotime($date));
}

function present_count($std_ids,$section_id){
    $count = 0;
    $std_ids = json_decode($std_ids, true);
    if(is_array($std_ids)){
        foreach($std_ids as $std){
            $student = \App\Models\User::find($std);
            if($student && $student->sections->where('section_id',$section_id)->count() > 0){
                $count++;
            }
        }
    }
    return $count;
}
