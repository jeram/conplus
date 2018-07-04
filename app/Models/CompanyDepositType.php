<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDepositType extends Model
{
    public function companies() {
		
        return $this->hasMany('App\Models\Company');
		
    }
}
