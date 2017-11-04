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
        $this->middleware('auth.basic', ['only' => ['strore', 'update']]);
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
