<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name'];
    public function office()
    {
       return $this->hasOne(Office::class);
    }
    
    public function headquarter()
    {
       return $this->hasOne(Headquarter::class);
    }
    
    public function states(){
        return $this->belongsTo(State::class,'state_id');
    }
    public function state(){
        return $this->belongsTo(State::class,'state_id');
    }
}
