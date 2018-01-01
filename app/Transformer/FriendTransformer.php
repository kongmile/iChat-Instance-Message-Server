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
        $user = [
            'id' => $item['id'],
            "name" =>  $item['name'],
            "email" => $item["email"],
            "isOnline" => Redis::get('isOnline:'.$item['id']) ? true : false,
            "isFriend" => !empty(Friend::where([['user2', $item['id']], ['user1', $this->auth->user()->id]])->count()),
            "avatar" => $avatar ?? "http://139.199.175.91/ichat/public/storage/files/AT1fZ4RkgS5rOesjeJwc7twPBN7bCk6CIbjUhr4x.png",
            "sex" => $sex ?? null,
            "birthday" => $birthday ?? null,
            "area" => $area ?? null,
            "signature" => $signature ?? null,
        ];
        if($user['isFriend']) {
            $user['tag'] = Friend::where([['user2', $item['id']], ['user1', $this->auth->user()->id]])->first()->tag->toArray();
        }
        return $user;
    }
}