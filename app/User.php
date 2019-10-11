<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserWelcomeMail;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected static function boot(){
        parent::boot();

        static::created(function($user){
            $user->profile()->create([
                'title' => $user->username,
            ]);

            Mail::to($user->email)->send(new NewUserWelcomeMail($user->name));
        });
    }

    /**
     * Creating a relationship to Profile table
     * One-To-One
     */
    public function profile(){
        return $this->hasOne(Profile::class);
    }

    /**
     * Creating a relationship to Post Table:
     * One-to-Many
     */
    public function posts(){
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }

    /**
     * Creating a relationship to Following(Profiles):
     * "A User can follow many profiles."
     * Many-To-Many
     */
    public function following(){
        return $this->belongsToMany(Profile::class);
    }
}
