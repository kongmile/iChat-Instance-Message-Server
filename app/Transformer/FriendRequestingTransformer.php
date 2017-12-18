<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 2017/11/26
 * Time: 20:44
 */

namespace App\Transformer;


class FriendRequestingTransformer extends Transformer
{
    public function transform($item)
    {
        $item['to'] = $item->toUser->toArray();
        $item['from'] = $item->fromUser->toArray();
        return $item;
    }
}