<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyEquipment extends Model
{
    public function company() {
		
        return $this->belongsTo('App\Models\Company');
		
    }
	
	public function history() {
		
        return $this->hasMany('App\Models\CompanyEquipmentHistory');
		
    }

    public function status() {
		
        return $this->hasOne('App\Models\CompanyEquipmentStatus', 'id', 'company_equipment_status_id');
		
    }
}
