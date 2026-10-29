<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    
    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function answers ()
    {
       return $this->hasMany(Answer::class);
    }

    

    
}
