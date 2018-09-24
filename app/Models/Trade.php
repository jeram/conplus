<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    public function setPaymentDateAttribute($value) {
        if (!$value) {
            return;
        }
    	$date = \DateTime::createFromFormat('M j, Y', $value);
        $this->attributes['payment_date'] = $date->format('Y-m-d');
    }
    
    public function client() {
		
        return $this->belongsTo('App\Models\Client');
		
    }

    public function status_options() {
		
        return $this->hasMany('App\Models\TradeStatus');
		
    }
}
