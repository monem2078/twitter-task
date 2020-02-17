<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'date_of_birth', 'age', 'image',
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

    public static function rules($action, $id = null)
    {
        switch ($action) {
            case 'register':
                return array(
                    'name' => 'required',
                    'password' => 'required|min:6',
                    'email' => 'required|email',
                    'date_of_birth' => 'required|date',
                    'image' => 'required|mimes:jpeg,jpg,png'
                );
        }
    }

    public static function messages()
    {
        return [
            'name.required' => trans('validation.name_required'),
            'password.required' => trans('validation.password_required'),
            'password.min' => trans('validation.password_min'),
            'email.required' => trans('validation.email_required'),
            'email.email' => trans('validation.email_email'),
            'date_of_birth.required' => trans('validation.date_required'),
            'date_of_birth.date' => trans('validation.date_date'),
            'image.required' => trans('validation.image_required'),
            'image.mimes' => trans('validation.image_mimes'),
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follows', 'follower_id', 'following_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follows', 'following_id', 'follower_id');
    }
}
