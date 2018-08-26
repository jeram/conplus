<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
    
	public function company() {
		
        return $this->belongsTo('App\Models\Company');
		
    }
	
	public function attachments() {
		
        return $this->hasMany('App\Models\ProjectAttachment');
		
    }
	
	public function details() {
		
        return $this->hasOne('App\Models\ProjectDetail');
		
    }

    public function status() {
		
        return $this->hasOne('App\Models\ProjectStatus');
		
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
