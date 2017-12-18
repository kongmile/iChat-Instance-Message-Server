<?php
namespace App\Api\Controllers;

use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Qcloud\Sms\SmsSingleSender;
use Illuminate\Support\Facades\Redis;

class AuthController extends BaseController
{
    public $appid = 1400051279;
    public $appkey = "113ec86386e08b6e3eea8c06473866c3";
    public $smsTTL = 180;

    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->response->error('invalid_credentials', 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->response->error('could_not_create_token', 500);
        }
        $user = User::where('email', $request->email)->first();
        // all good so return the token
        return $this->response->array(compact('token', 'user'));
    }

    public function register(Request $request)
    {
        $newUser = [
            'email' => $request->get('email'),
            'name' => $request->get('name'),
            'password' => bcrypt($request->get('password'))
        ];

        $user = User::create($newUser);
        $token = JWTAuth::fromUser($user);

        return $this->response->array(compact('token', 'user'));
    }

    public function getAuthenticatedUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->response->error('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return $this->response->error('token_expired', $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return $this->response->error('token_invalid', $e->getStatusCode());

        } catch (JWTException $e) {

            return $this->response->error('token_absent', $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return $this->response->array(compact('user'));
    }

    public function show()
    {
        return $this->response->array(User::all()->toArray());
    }

    public function registerSms(Request $request)
    {
        if ($request->has('phone')) {
            $phone = $request->phone;
            $key = 'rigister_sms:' . $phone;
            if ($request->has('code')) {
                if ($request->code == Redis::get($key)) {
                    $newUser = [
                        'email' => $request->get('phone'),
                        'name' => $request->get('name'),
                        'password' => bcrypt($request->get('password'))
                    ];

                    $user = User::create($newUser);
                    $token = JWTAuth::fromUser($user);

                    return $this->response->array(compact('token', 'user'));
                } else {
                    $this->response->error('验证码错误', 401);
                }
            } else {
                $sender = new SmsSingleSender($this->appid, $this->appkey);
                $authCode = rand(1000, 9999);
                $templId = 59199;
                $params = [$authCode, $this->smsTTL];
                // 假设模板内容为：测试短信，{1}，{2}，{3}，上学。
                $result = $sender->sendWithParam("86", $phone, $templId, $params, "", "", "");
                $rsp = json_decode($result);
                if ($rsp->result != 0) {
                    return $this->response->error('腾讯云短信服务:发送失败，频率过高', 403);
                } else {
                    if (Redis::exists($key)) {
                        return $this->response->error('ichat服务器:发送失败，频率过高', 403);
                    } else {
                        Redis::set($key, $authCode);
                        Redis::expire($key, $this->smsTTL);
                        return $this->response->array([
                            'message' => 'sms sent',
                            'key' => $key,
                        ]);
                    }
                }
            }
        } else {
            return $this->response->error('请输入正确的手机号', 401);
        }
    }
}
