<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchedulingSerObs extends Model
{
    protected $table = 'scheduling_service_observations';
    protected $guarded = ['id'];
    
    public function role(){
        return $this->belongsTo(\Caffeinated\Shinobi\Models\Role::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function file(){
        return $this->morphOne(File::class, 'filable');
    }
}
