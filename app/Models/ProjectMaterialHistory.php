<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMaterialHistory extends Model
{
    public function project_material() {
		
        return $this->belongsTo('App\Models\ProjectMaterial');
		
    }
}
