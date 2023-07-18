<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password','status','utype','status','qualification','experience',
    ];
	
	protected $appends = ['full_name'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
	
	public function getFullNameAttribute()
    {
		return $this->first_name." ".$this->last_name;
	}
	
	public function signature()
    {
        return $this->hasOne('App\Refefile', 'refe_field_id', 'id')->where('refe_table_field_name', 'user_id');
    }
	
	public function qualificationList()
    {
		if($this->experience){
			return json_decode($this->qualification,true);
		}else{
			return [];
		}
    }
	public function experienceList()
    {
        if($this->experience){
			return json_decode($this->experience,true);
		}else{
			return [];
		}
    }
	
}













