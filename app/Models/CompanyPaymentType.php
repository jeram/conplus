<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPaymentType extends Model
{
    public function companies() {
		
        return $this->hasMany('App\Models\CompanyEquipment');
		
    }
}
