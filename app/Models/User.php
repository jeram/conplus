<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoleAndPermission;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public function companies() {
		
        return $this->belongsToMany('App\Models\Company','user_to_companies');
		
    }
	
	/**
	 * Roll API Key
	 */
	public function rollApiKey() {
	   do {
		  $this->api_token = str_random(60);
	   } while ($this->where('api_token', $this->api_token)->exists());
	   $this->save();
	}
}
