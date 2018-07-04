<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectNoteStatus extends Model
{
    public function project_note() {
		
        return $this->hasOne('App\Models\ProjectNote');
		
    }
}
