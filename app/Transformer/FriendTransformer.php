<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 2017/11/26
 * Time: 19:25
 */

namespace App\Transformer;

use App\Friend;
use Dingo\Api\Routing\Helpers;
use App\User;
use Illuminate\Support\Facades\Redis;
class FriendTransformer extends Transformer
{
    use Helpers;
    public function transform($item)
    {

        if($profile = User::findOrFail($item['id'])->profile) {
            $avatar = $profile->user_img;
            $area = $profile->user_area;
            $birthday = $profile->user_birthday;
            $signature = $profile->user_qianming;
            $sex = $profile->user_sex;
        }
        return [
            'id' => $item['id'],
            "name" =>  $item['name'],
            "email" => $item["email"],
            "isOnline" => Redis::get('isOnline:'.$item['id']) ? true : false,
            "isFriend" => !empty(Friend::where([['user2', $item['id']], ['user1', $this->auth->user()->id]])->count()),
            "avatar" => $avatar ?? null,
            "sex" => $sex ?? null,
            "birthday" => $birthday ?? null,
            "area" => $area ?? null,
            "signature" => $signature ?? null,
        ];
    }
}