<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
	
    public function deposit_types() {
		
        return $this->hasMany('App\Models\CompanyDepositType');
		
    }
	
	public function equipments() {
		
        return $this->hasMany('App\Models\CompanyEquipment');
		
    }

	public function equipment_statuses() {
		
        return $this->hasMany('App\Models\CompanyEquipmentStatus');
		
    }
	
	public function payment_types() {
		
        return $this->hasMany('App\Models\CompanyPaymentType');
		
    }
	
	public function vendors() {
		
        return $this->hasMany('App\Models\CompanyVendor');
		
    }
	
	public function projects() {
		
        return $this->hasMany('App\Models\Project');
		
    }

    public function clients() {
		
        return $this->hasMany('App\Models\Client');
		
    }

    public function active_projects() {
		
        return $this->hasMany('App\Models\Project')->where('projects.is_active', 1);
		
    }
	
	public function users() {
		
		return $this->belongsToMany('App\Models\Company','user_to_companies');
		
    }
	
	public function customers() {
		
        return $this->hasMany('App\Models\Customer');
		
    }

    public function project_statuses() {
		
        return $this->hasMany('App\Models\ProjectStatus');
		
    }

    public function project_types() {
		
        return $this->hasMany('App\Models\ProjectType');
		
    }

    public function project_note_statuses() {
		
        return $this->hasMany('App\Models\ProjectNoteStatus');
		
    }

    public function project_material_statuses() {
		
        return $this->hasMany('App\Models\ProjectMaterialStatus');
		
    }

    public function units() {
		
        return $this->hasMany('App\Models\UnitOfMeasurement');
		
    }

    public function trade_statuses() {
		
        return $this->hasMany('App\Models\TradeStatus');
		
    }

    public function payments() {
		
        return $this->hasMany('App\Models\ProjectPayment');
		
    }
	
}
