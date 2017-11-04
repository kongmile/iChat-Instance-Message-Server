<?php

namespace App\Api\Controllers;

use App\Api\Transformer\LessonTransformer;
use Illuminate\Http\Request;
use App\Lesson;

class LessonController extends BaseController
{
    public function index()
    {
        $lessons = Lesson::all();
        return $this->collection($lessons, new LessonTransformer());

    }

    public function show($id)
    {
        $lesson = Lesson::find($id);
        if(! $lesson) {
            return $this->setStatusCode(404)->responseNotFound(); // 不要忘记加return
        }
        return $this->response([
            'status' => 'success',
            'status_code' => '200',
            'data' => $this->lessonTransformer->transform($lesson),
        ]);
    }

    public function store(Request $request)
    {
        if(! $request->get('title') or ! $request->get('body')) {
            return $this->setStatusCode(422)->responseError('validate fails');
        }
        Lesson::create($request->all());
        return $this->setStatusCode(200)->response([
            'status' => 'success',
            'message' => 'lesson created',
        ]);
    }
}
