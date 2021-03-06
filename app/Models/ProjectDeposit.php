<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDeposit extends Model
{
	public function setPaymentDateAttribute($value) {
        if (!$value) {
            return;
        }
    	$date = \DateTime::createFromFormat('M j, Y', $value);
        $this->attributes['payment_date'] = $date->format('Y-m-d');
    }

    public function project() {
		
        return $this->belongsTo('App\Models\Project');
		
    }

    public function type() {
		
        return $this->hasOne('App\Models\CompanyDepositType', 'id', 'company_deposit_type_id');
		
    }
	
	public function type_options() {
		
        return $this->hasMany('App\Models\CompanyDepositType');
		
    }
}
