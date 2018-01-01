<?php

namespace App\Api\Controllers;

use App\Friend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tag;

class TagController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        var_dump($this->auth->user()->tags());
        return $this->response->array($this->auth->user()->tags->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = new Tag();
        $tag->name = $request->name;
        $tag->user_id = $this->auth->user()->id;
        $tag->save();
        return $this->response->array($tag->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::find($request->id);
        if(!$tag) {
            return $this->response->error('Tag Not Found', 404);
        }
        if($tag->user_id != $this->auth->user()->id) {
            return $this->response->error('This tag do not belong to you', 403);
        }
        $tag = Tag::find($id);
        $tag->name = $request->name;
        $tag->save();
        return $this->response->array($tag->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        if(!$tag) {
            return $this->response->error('Tag Not Found', 404);
        }
        if($tag->user_id != $this->auth->user()->id) {
            return $this->response->error('This tag do not belong to you', 403);
        }
        $tag = Tag::find($id);
        if(!$tag->members) {
            return $this->response->error('被删除的分组必须为空', 406);
        }
        $tag->delete();
        return $this->response->array($tag->toArray());
    }

    public function put(Request $request, $user_id, $tag_id){
        $tag = Tag::find($tag_id);
        if(!$tag) {
            return $this->response->error('Tag Not Found', 404);
        }
        if($tag->user_id != $this->auth->user()->id) {
            return $this->response->error('This tag do not belong to you', 403);
        }
        $friend = Friend::where([['user1', $this->auth->user()->id],['user2', $request->user_id]])->first();
        if(!$friend)
            return $this->response->error('You are not friend', 405);
        $friend->tag_id = $tag_id;
        $friend->save();
        return $this->response->array(Tag::find($tag_id)->toArray());
    }
}
