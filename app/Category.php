<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];


    public function productos ()
    {
        return $this->hasMany(Producto::class,'id');
 
    }


}
