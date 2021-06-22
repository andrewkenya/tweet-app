<?php

namespace App\Models;


use App\Followable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Followable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        
    ];

    
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

   
        


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarAttribute()
    {
        return "https://i.pravatar.cc/200?u=". $this->email;
    }

    public function timeline()
    {
        $friends = $this->follows()->pluck('id');
       

        return Tweet::whereIn('user_id', $friends)
        ->orWhere('user_id', $this->id)
        ->latest()->get();
    }

    public function tweets()
    {

        return $this->hasMany(Tweet::class)->latest();
    }


    

    // public function getRouteKeyName()
    // {
    //     return 'name';
    // }

    public function resolveRouteBinding($value, $field = null)
{
    return $this->where('name', $value)->firstOrFail();
}




    public function path()
    {
        return route('profile', $this->name);
    }
}
