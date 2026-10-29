<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    protected $guarded = ["id"];
    
    public function availabilities() {
        return $this->belongsTo(Availability::class, 'availability_id');
    }
}
