<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasurement extends Model
{
    protected $table = 'unit_of_measurements';

    public function material() {
		
        return $this->hasMany('App\Models\Material');
		
    }
}
