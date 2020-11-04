<?php


namespace App\Service;


use App\Models\BodyValue;

class BodyValueService
{
    public function add_body_value($user_id){
        return BodyValue::create([
            'user_id'=>$user_id
        ]);
    }

}
