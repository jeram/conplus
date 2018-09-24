<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class TradeStatus extends Model
{
    public function trade() {
		
        return $this->hasOne('App\Models\Trade');
		
    }
}
