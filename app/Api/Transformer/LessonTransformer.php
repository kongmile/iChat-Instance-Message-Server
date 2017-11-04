<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 2017/11/4
 * Time: 16:12
 */

namespace App\Api\Transformer;
use App\Lesson;
use League\Fractal\TransformerAbstract;


class LessonTransformer extends TransformerAbstract
{
    public function transform(Lesson $lesson) {
        return [
            'title' => $lesson['title'],
            'body' => $lesson['body'],
            'is_free' => (boolean)$lesson['free']
        ];
    }
}