<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['name','enabled'];

    public function productos ()
    {
        return $this->hasMany(Product::class,'id');
 
    }

    function user(){
        return $this->belongsTo(User::class,'updated_user');
     }
}
