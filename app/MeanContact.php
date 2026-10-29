<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeanContact extends Model
{

    protected $guarded = ['id'];

    public function crm ()
    {
       return $this->hasOne(Crm::class);
    }
}
