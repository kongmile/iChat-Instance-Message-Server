<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 2017/11/4
 * Time: 11:09
 */

namespace App\Transformer;


class LessonTramsformer extends Transformer
{

    public function transform($lesson) {
        return [
            'title' => $lesson['title'],
            'body' => $lesson['body'],
            'is_free' => (boolean)$lesson['free']
        ];
    }
}