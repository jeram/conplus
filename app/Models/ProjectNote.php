<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectNote extends Model
{
    public function project() {
		
        return $this->belongsTo('App\Models\Project');
		
    }
	
	public function status_options() {
		
        return $this->hasMany('App\Models\ProjectNoteStatus');
		
    }
}
