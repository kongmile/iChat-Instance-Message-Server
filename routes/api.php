<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::resource('lesson', 'LessonController');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Api\Controllers'], function ($api) {
        $api->post('user/login', 'AuthController@authenticate');
        $api->post('user/register', 'AuthController@register');
        $api->get('user', 'AuthController@show');
        $api->post('user/register/sms', 'AuthController@registerSms');
        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->get('profile/me','AuthController@getAuthenticatedUser');
            $api->get('lesson', 'LessonController@index');
            $api->get('lesson/{id}','LessonController@show');

            $api->post('friend_requesting', 'FriendController@createFriendRequesting');
            $api->get('friend_requesting/agree/{id}', 'FriendController@agreeFriendRequesting');
            $api->get('friend_requesting/ignore/{id}', 'FriendController@ignoreFriendRequesting');
            $api->get('friend/{id}', 'FriendController@show');
            $api->get('friend', 'FriendController@index');
            $api->delete('friend/{id}', 'FriendController@destroy');
            $api->post('friend/search', 'FriendController@search');

            $api->post('msg', 'ChatController@create');
        });
    });
});
