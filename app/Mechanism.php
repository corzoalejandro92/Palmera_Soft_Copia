<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mechanism extends Model
{
    protected $fillable = ['name'];


    public function productos ()
    {
        return $this->hasMany(Product::class,'id');
 
    }
}
