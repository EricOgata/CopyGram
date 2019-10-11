<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];

    public function profileImage(){
        $imagePath = ($this->image) ? $this->image : "profile/nophoto.png";
        return "/storage/" . $imagePath;
    }

    /**
     * Creating a relationship to User table
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Creating a relationship to Followers(Users):
     * "A profile can have many followers (users)".
     * Many-To-Many
     */
    public function followers(){
        return $this->belongsToMany(User::class);
    }
}
