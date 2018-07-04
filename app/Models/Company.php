<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	
    public function deposit_type() {
		
        return $this->hasOne('App\Models\CompanyDepositType');
		
    }
	
	public function equipments() {
		
        return $this->hasMany('App\Models\CompanyEquipment');
		
    }
	
	public function payment_type() {
		
        return $this->hasOne('App\Models\CompanyPaymentType');
		
    }
	
	public function vendors() {
		
        return $this->hasMany('App\Models\CompanyVendor');
		
    }
	
	public function projects() {
		
        return $this->hasMany('App\Models\Project');
		
    }
	
	public function users() {
		
        return $this->hasMany('App\Models\User','user_to_companies','company_id','user_id');
		
    }
	
	public function customers() {
		
        return $this->hasMany('App\Models\Customer');
		
    }
	
}
