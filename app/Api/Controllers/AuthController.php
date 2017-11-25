<?php
namespace App\Api\Controllers;

use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
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

    public function register(Request $request) {
        $newUser = [
            'email' => $request->get('email'),
            'name' => $request->get('name'),
            'password' => bcrypt($request->get('password'))
        ];

        $user = User::create($newUser);
        $token = JWTAuth::fromUser($user);

        return $this->response->array(compact('token'));
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
}