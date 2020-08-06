<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
	use Authenticatable, Authorizable;
 
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    public $timestamps = true;

    protected $hidden = [
    	'password'
    ];

    public function getJWTIdentifier()
    {
    	return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
    	return [];
    }

    public function sewa(){
        return $this->hasMany('App\Models\Sewa');
    }
}

