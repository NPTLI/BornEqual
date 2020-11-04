<?php


namespace App\Service;


use App\Models\Wealth;

class WealthService
{
    public function add_wealth($user_id){
        return Wealth::create([
            'user_id'=>$user_id
        ]);
    }
}
