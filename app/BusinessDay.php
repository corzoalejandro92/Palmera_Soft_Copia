<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessDay extends Model
{
    protected $guarded = ["id"];
    
    public function availabilities(){
        return $this->hasMany(Availability::class);
    }
}
