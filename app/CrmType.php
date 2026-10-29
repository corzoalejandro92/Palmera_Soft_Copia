<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmType extends Model
{
    protected $guarded = ['id'];
    public function crm ()
    {
       return $this->hasOne(Crm::class);
    }
}
