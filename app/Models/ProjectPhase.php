<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPhase extends Model
{
    public function project() {
		
        return $this->belongsTo('App\Models\Project');
		
    }
}
