<?php

namespace App\Api\Controllers;

use App\Events\FriendRequestingCreated;
use Illuminate\Http\Request;
use App\FriendRequesting;
use Dingo\Api\Routing\Helpers;
use App\Events\FriendRequestingSent;

class FriendController extends BaseController
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * 创建好友请求
     *
     * @return \Illuminate\Http\Response
     */
    public function createFriendRequesting(Request $request)
    {
        $FriendRequesting = new FriendRequesting();
        $FriendRequesting->from = $this->auth->user()->id;
        $FriendRequesting->to = $request->to;
        $FriendRequesting->message = $request->message;
        $FriendRequesting->save();
        broadcast(new FriendRequestingCreated($FriendRequesting));
        return $this->response->array([
            'message' => 'FriendRequesting sent',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
