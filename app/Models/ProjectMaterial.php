<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMaterial extends Model
{
    public function project() {
		
        return $this->belongsTo('App\Models\Project');
		
    }
	
	public function material_details() {
		
        return $this->hasOne('App\Models\Material');
		
    }
	
	public function status_options() {
		
        return $this->hasMany('App\Models\ProjectMaterialStatus');
		
    }
}
