<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPhase extends Model
{
    public function project() {
		
        return $this->belongsTo('App\Models\Project');
		
    }

    public function setStartDateAttribute($value) {
        if (!$value) {
            return;
        }
    	$date = \DateTime::createFromFormat('M j, Y', $value);
        $this->attributes['start_date'] = $date->format('Y-m-d');
    }

    public function setFinishDateAttribute($value) {
        if (!$value) {
            return;
        }
    	$date = \DateTime::createFromFormat('M j, Y', $value);
        $this->attributes['finish_date'] = $date->format('Y-m-d');
    }

    public function materials() {
		
        return $this->hasMany('App\Models\ProjectMaterial');
		
    }

    /*public function getStartDateAttribute() {
        if ($this->start_date) {
            return;
        }
    	$date = \DateTime::createFromFormat('Y-m-d', $this->start_date);
        $this->attributes['start_date'] = $date->format('m/d/Y');
    }

    public function getFinishDateAttribute() {
        if ($this->finish_date) {
            return;
        }
    	$date = \DateTime::createFromFormat('Y-m-d', $this->finish_date);
        $this->attributes['finish_date'] = $date->format('m/d/Y');
    }*/
}
