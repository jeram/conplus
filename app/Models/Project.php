<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    
	public function company() {
		
        return $this->belongsTo('App\Models\Company');
		
    }
	
	public function attachments() {
		
        return $this->hasMany('App\Models\ProjectAttachment');
		
    }
	
	public function details() {
		
        return $this->hasOne('App\Models\ProjectDetail');
		
    }
	
	public function materials() {
		
        return $this->hasMany('App\Models\ProjectMaterial');
		
    }
	
	public function notes() {
		
        return $this->hasMany('App\Models\ProjectNote');
		
    }
	
	public function payments() {
		
        return $this->hasMany('App\Models\ProjectPayment');
		
    }
	
	public function phases() {
		
        return $this->hasMany('App\Models\ProjectPhase');
		
    }
	
	public function schedules() {
		
        return $this->hasMany('App\Models\ProjectSchedule');
		
    }
}
