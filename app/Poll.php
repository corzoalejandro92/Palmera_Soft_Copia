<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poll extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function questions ()
   {
      return $this->hasMany(Question::class);
   }

}
