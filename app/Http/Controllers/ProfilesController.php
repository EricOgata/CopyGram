<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Cache;

class ProfilesController extends Controller {
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(User $user)
    {        
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;        

        $postsCount = Cache::remember(
            'count.posts.' . $user->id, // Cache ID
            now()->addSeconds(30),      // Cached Time 
            function() use ($user){     // Callback data
                return $user->posts->count();
            });
        $followersCount = Cache::remember(
            'count.followers.' . $user->id, // Cache ID
            now()->addSeconds(30),      // Cached Time 
            function() use ($user){     // Callback data
                return $user->profile->followers->count();
            });
        $followingCount = Cache::remember(
            'count.following.' . $user->id, // Cache ID
            now()->addSeconds(30),      // Cached Time 
            function() use ($user){     // Callback data
                return $user->following->count();
            });

        return view('profiles.index',compact('user', 'follows', 'postsCount', 'followersCount', 'followingCount'));
    }

    public function edit(User $user){
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));        
    }

    public function update(User $user){
        $this->authorize('update', $user->profile);

        // validation rules
        $data = request()->validate([
            'title'         =>  'required',
            'description'   =>  'required',
            'url'           =>  'url',
            'image'         =>  ''
        ]);        

        if(request('image')){
            $imagePath = request('image')->store('profile', 'public');
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000,1000);
            $image->save();
            $imageArray = ['image' => $imagePath];
        }

        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? []
        ));

        return redirect("/profile/{$user->id}");
    }
}
