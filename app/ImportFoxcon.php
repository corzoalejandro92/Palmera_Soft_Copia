<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportFoxcon extends Model
{
    use SoftDeletes;
    
    protected $guarded = ['id'];

    public function file(){
        return $this->morphOne(File::class, 'filable');
    }

    public function files(){
        return $this->morphMany(File::class, 'filable');
    }
}
