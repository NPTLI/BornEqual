<?php

namespace App\Http\Controllers;

use App\ApiCode\CodeMessage;
use App\Service\UserService;

class AuthController extends Controller
{
    protected $userService;
    /**
     * Create a new AuthController instance.
     * 要求附带email和password（数据来源users表）
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        // 这里额外注意了：官方文档样例中只除外了『login』
        // 这样的结果是，token 只能在有效期以内进行刷新，过期无法刷新
        // 如果把 refresh 也放进去，token 即使过期但仍在刷新期以内也可刷新
        // 不过刷新一次作废
        $this->middleware('jwt.auth', ['except' => ['login','registered','refresh']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
        $this->userService=$userService;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['username', 'password']);
        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'code'=>CodeMessage::ErrorFail[0],
                'msg'=>CodeMessage::ErrorFail[1],
                'data'=>''
            ]);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     */
    protected function respondWithToken($token)
    {
        return [
            'code'=>CodeMessage::OK[0],
            'msg'=>CodeMessage::OK[1],
            'data'=>$token
        ];
    }

    public function registered(){
        $data=request()->all('username','password');
        $data['real_name']=request()->input('real_name','');
        $data['sex']=request()->input('sex',2);
        $data['gmail']=request()->input('gmail','');
        $response=[
            'code'=>0,
            'msg'=>'',
            'data'=>[]
        ];
        if (!request()->filled('username','password')) {
            $response['code']=CodeMessage::ErrorParameter[0];
            $response['msg']=CodeMessage::ErrorParameter[1];
        }else{
            $user=$this->userService->add_user($data);

            if ($user){
                $info=[
                    'code'=>CodeMessage::OK[0],
                    'msg'=>CodeMessage::OK[1],
                    'data'=>$user
                ];
            }else{
                $info=[
                    'code'=>CodeMessage::ErrorFail[0],
                    'msg'=>CodeMessage::ErrorFail[1],
                    'data'=>$user
                ];
            }
            $response=$info;
        }



        return response()->json($response);
    }
}
