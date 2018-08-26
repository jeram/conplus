<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    public function categories() {
		
        return $this->hasMany('App\Models\MaterialCategory');
		
    }

    public function unit() {
		
        return $this->belongsTo('App\Models\UnitOfMeasurement', 'unit_of_measurement_id');
		
    }
}
