<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyEquipmentHistory extends Model
{
    public function setDateAttribute($value) {
        if (!$value) {
            return;
        }
    	$date = \DateTime::createFromFormat('M j, Y', $value);
        $this->attributes['date'] = $date->format('Y-m-d');
    }

    public function equipment() {
		
        return $this->belongsTo('App\Models\CompanyEquipment');
		
    }
}
