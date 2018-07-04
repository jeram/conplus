<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyEquipmentHistory extends Model
{
    public function equipment() {
		
        return $this->belongsTo('App\Models\CompanyEquipment');
		
    }
}
