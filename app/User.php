<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Exception;
use League\OAuth2\Server\Exception\OAuthServerException;
use Throwable;

class User extends Authenticatable
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'phone_number',
        'is_active', 'new_password', 'password', 'confirm_password', 'created_at', 'updated_at'
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

    public function validateForPassportPasswordGrant($password)
    {
        if (Hash::check($password, $this->getAuthPassword())) {
            //is user active?
            if ($this->is_active == 1) {
                return true;
            } else {
                throw new OAuthServerException('Your account is not active', 6, 'account_inactive', 401);
            }
        }
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_role');
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

}
