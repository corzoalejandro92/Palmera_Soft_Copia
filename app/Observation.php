<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Observation extends Model
{

    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['file_url'];
    public function scheduling(){
        return $this->belongsTo(Scheduling::class);
    }

    public function getFileUrlAttribute(){
        return $this->file_path ? Storage::disk('public')->url($this->file_path) : null;
    }

}
