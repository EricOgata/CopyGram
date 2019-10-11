<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Post;

class PostsController extends Controller {
    
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * method handling the INDEX route of Posts
     */
    public function index(){
        $users = auth()->user()->following()->pluck('profiles.user_id');
        $posts = Post::whereIn('user_id',$users)->with('user')->latest()->paginate(2);

        return view('posts.index', [
            'posts' => $posts
        ]);
    }

    /**
     * method handling the create route of Posts
     */
    public function create(){
        return view('posts.create');
    }

    /**
     * method handling the store route of Posts
     */
    public function store(){
        $data = request()->validate([
            'caption'   => 'required',
            'image'     => ['required', 'image'],
        ]);

        $imagePath = request('image')->store('uploads', 'public');

        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200,1200);
        $image->save();

        auth()->user()->posts()->create([
            'caption'   => $data['caption'],
            'image'     => $imagePath,
        ]);

        return redirect('/profile/' . auth()->user()->id);
    }

    public function show(\App\Post $post){
        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
