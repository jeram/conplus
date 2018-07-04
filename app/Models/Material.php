<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    public function categories() {
		
        return $this->hasMany('App\Models\MaterialCategory');
		
    }
}
