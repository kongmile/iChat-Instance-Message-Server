<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 2017/12/23
 * Time: 21:11
 */

namespace App\Transformer;
use App\Transformer\FriendTransformer;
use App\Tag;
class TagTransformer extends Transformer
{
    public function transform($item)
    {
        $friends = Tag::findOrFail($item['id'])->members;
        $members = [];
        $tansformer = new FriendTransformer();
        foreach($friends as $friend) {
            $members[] = $tansformer->transform($friend->friend);
        }
        return [
            'id' => $item['id'],
            'tags' => $item['name'],
//            'members' => (new FriendTransformer())->transformCollection(Tag::findOrFail($item['id'])->members->toArray()),
            'members' => $members,
        ];
    }
}