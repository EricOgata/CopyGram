<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    /**
     * Relationship with User Table; Many-To-One 
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
