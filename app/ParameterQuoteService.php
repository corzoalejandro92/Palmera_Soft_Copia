<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParameterQuoteService extends Model
{
    protected $guarded = ['id'];

    public function parameter(){
        return $this->belongsTo(ParameterService::class);
    }
}
