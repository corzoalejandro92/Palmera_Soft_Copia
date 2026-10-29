<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitDose extends Model
{
    protected $fillable = ['name','enabled'];

    public function doses ()
    {
        return $this->hasMany(Doses::class,'id');
 
    }

    function user(){
        return $this->belongsTo(User::class,'user_id');
     }
}
