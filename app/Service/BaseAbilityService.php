<?php


namespace App\Service;


use App\Models\BaseAbility;

class BaseAbilityService
{
    public function add_base_ability($user_id){
        return BaseAbility::create([
            'user_id'=>$user_id
        ]);
    }
}
