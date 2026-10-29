<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Answer extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    
    public function question()
    {
        return $this->belongsTo(Question::class,'');
    }
}
