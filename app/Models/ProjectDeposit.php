<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDeposit extends Model
{
    public function project() {
		
        return $this->belongsTo('App\Models\Project');
		
    }
	
	public function type_options() {
		
        return $this->hasMany('App\Models\CompanyDepositType');
		
    }
}
