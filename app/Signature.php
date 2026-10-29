<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Signature extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['file_url'];
    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function scheduling(){
        return $this->belongsTo(Scheduling::class, 'scheduling_id');
    }

    public function getFileUrlAttribute(){
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

}
