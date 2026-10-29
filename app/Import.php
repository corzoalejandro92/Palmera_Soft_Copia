<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $guarded = ['id'];

    public function file(){
        return $this->morphOne(File::class, 'filable');
    }

    public function files(){
        return $this->morphMany(File::class, 'filable');
    }
}
