<?php


namespace App\Http\Controllers;



use Tymon\JWTAuth\Facades\JWTAuth;

class GameControllers extends Controller
{
    public function buyLottery(){
        $user=auth('api')->user();

    }

}
