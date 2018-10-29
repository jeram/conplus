<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProjectStatus;
use App\Models\Project;

class ProjectDetail extends Model
{
    public static function boot(){
        static::updated(function($detail){
            if($detail->isDirty('project_status_id') && $detail->getStatusLabel() == 'Completed'){
               // update project
               $project = Project::find($detail->project_id);
               $project->is_active = 0;
               $project->save();
            }
        });
    }

    public function project() {
		
        return $this->belongsTo('App\Models\Project');
		
    }
	
	public function type_options() {
		
        return $this->hasMany('App\Models\ProjectType');
		
    }

    public function getStatusLabel() {

        if (!$this->project_status_id) {
            return '';
        }

        return ProjectStatus::find($this->project_status_id)->label;

    }
}
