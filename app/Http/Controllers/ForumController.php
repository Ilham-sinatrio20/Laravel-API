<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForumRequest;
use App\Http\Resources\ForumsResource;
use App\Models\Forum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForumController extends Controller {

    use AuthUserTrait;

    // public function getAuthorized(){
    //     try {
    //         return auth()->userOrFail();
    //     } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $th) {
    //         response()->json(['message' => 'not authentication, you have to login'])->send();
    //         exit;
    //     }
    // }

    // public function checkOwnership($authUser, $forum){
    //     if($authUser != $forum){
    //         response()->json(['message' => 'You are not authorized to update this forum'])->send();
    //         exit();
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
        return ForumsResource::collection(
            Forum::with('user')->withCount('comments')->paginate(5)
        );
        // return Forum::with('user:id,username')->paginate(3);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ForumRequest $request) {
        $validator = $request->validated();
        $user = $this->getAuthorized();

        $user->forums()->create([
            'title' => request('title'),
            'body' => request('body'),
            'slug' => Str::slug(request('title'), '-') . '-' . time(),
            'category' => request('category')
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
        return Forum::with('user:id,username', 'comments.user:id,username')->find($id);
    }

    public function filterTag($tag) {
        return Forum::with('user')->where('category', $tag)->paginate(3);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ForumRequest $request, $id) {
        $validator = $request->validated();
        // $user = $this->getAuthorized();
        $forum = Forum::find($id);
        $this->checkOwnership($forum->user_id);

        $forum->update([
            'title' => request('title'),
            'body' => request('body'),
            'category' => request('category')
        ]);

        return response()->json(['message' => 'Forum has successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $forum = Forum::find($id);
        $this->checkOwnership($forum->user_id);

        $forum->delete();
        return response()->json(['message' => 'Forum has successfully deleted']);
    }
}


        // if($validator->fails()){
        //     return response()->json($validator->messages());
        // }
