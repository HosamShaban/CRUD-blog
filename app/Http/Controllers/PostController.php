<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function index()
    {
        $postFromDB = Post::all();

        return view('posts.index', ['posts' => $postFromDB]);
    }

    public function show(Post $post)
    {
        return view('posts.show', ['post' => $post]);
    }

    public function create()
    {
        $users = User::all();
        return view('posts.create', ['users' => $users]);
    }

    public function store()
    {

        request()->validate([
            'title' => ['required', 'min:3'],
            'description' => ['required'],
            'post_creator' => ['exists:users,id'],
        ]);
        $data = request()->all();
        $title = request()->title;
       $description = request()->description;
        $postCreator = request()->post_creator;


       // dd($data , $title, $description ,$postCreator);

        Post::create([
            'title' => $title,
            'description' => $description,
            'user_id' => $postCreator,
        ]);

        //3- redirection to posts.index
        return to_route('posts.index');
    }

    public function edit(Post $post)
    {
        $users = User::all();

        return view('posts.edit' , ['post' => $post, 'users' => $users]);
    }

    public function update($postId)
    {
        $title = request()->title;
        $description = request()->description;
        $postCreator = request()->post_creator;
        //dd($title, $description ,$postCreator);

        $singlePostFromDB = Post::find($postId);
        $singlePostFromDB->update([
            'title' => $title,
            'description' => $description,
            'user_id' => $postCreator,
        ]);
        return to_route('posts.show',$postId);
    }
    public function destroy($postId)
    {
        $post = Post::find($postId);
        $post->delete();

     //   Post::where('id', $postId)->delete();
        return to_route('posts.index');
    }
}
