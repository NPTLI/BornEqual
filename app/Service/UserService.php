<?php


namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $bodyValueService,$baseAbilityService,$wealthService;

    public function __construct(BodyValueService $bodyValueService,BaseAbilityService $baseAbilityService,WealthService $wealthService)
    {
        $this->bodyValueService=$bodyValueService;
        $this->baseAbilityService=$baseAbilityService;
        $this->wealthService=$wealthService;
    }

    public function add_user($data){
        return DB::transaction(function () use($data) {

            $user=User::create([
                'username'=>$data['username'],
                'real_name'=>$data['real_name'],
                'password'=>Hash::make($data['password']),
                'sex'=>$data['sex'],
                'gmail'=>$data['gmail'],
            ]);

            $this->bodyValueService->add_body_value($user['id']);

            $this->baseAbilityService->add_base_ability($user['id']);

            $this->wealthService->add_wealth($user['id']);

            return $user;
        });
    }
}
