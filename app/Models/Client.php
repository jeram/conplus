<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function company() {
		
        return $this->belongsTo('App\Models\Company');
		
    }

    public function trades() {
		
        return $this->hasMany('App\Models\Trade');
		
    }
}
