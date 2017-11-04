<?php

namespace App\Http\Controllers;

use App\Transformer\LessonTramsformer;
use Illuminate\Http\Request;
use App\Lesson;

class LessonController extends ApiController
{
    protected $lessonTransformer;

    public function __construct(LessonTramsformer $lessonTramsformer)
    {
        $this->lessonTransformer = $lessonTramsformer;
    }

    public function index()
    {
        $lessons = Lesson::all();
        return $this->response([
            'status' => 'success',
            'status_code' => '200',
            'data' => $this->lessonTransformer->transformCollection($lessons->toArray()),
        ]);
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
}
