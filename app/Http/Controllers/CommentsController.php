<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentsRequest;
use App\Http\Requests\ForumRequest;
use App\Models\Forum;
use App\Models\ForumsComments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CommentsController extends Controller {

    use AuthUserTrait;
    // public function getAuthorized(){
    //     try {
    //         return auth()->userOrFail();
    //     } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $th) {
    //         response()->json(['message' => 'not authentication, you have to login'])->send();
    //         exit;
    //     }
    // }

    public function __construct() {
        return auth()->shouldUse('api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return ForumsComments::with('user:id,username')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentsRequest $request, $forumId) {
        $validator = $request->validated();
        $comments = $this->getAuthorized();

        $comments->forumComment()->create([
            'body' => request('body'),
            'forum_id' => $forumId
        ]);

        return response()->json(['message' => 'Forum has successfully created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentsRequest $request, $forumId, $commentsId) {
        $forumsComments = ForumsComments::find($commentsId);
        $this->checkOwnership($forumsComments->user_id);

        $forumsComments->update([
            'body' => request('body')
        ]);

        return response()->json(['message' => 'Comments has successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($forumId, $commentsId) {
        $forumsComments = ForumsComments::find($commentsId);
        $this->checkOwnership($forumsComments->user_id);

        $forumsComments->delete();
        return response()->json(['message' => 'Comments has successfully deleted']);
    }
}
