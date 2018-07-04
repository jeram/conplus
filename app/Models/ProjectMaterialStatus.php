<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMaterialStatus extends Model
{
    public function project_material() {
		
        return $this->hasOne('App\Models\ProjectMaterial');
		
    }
}
