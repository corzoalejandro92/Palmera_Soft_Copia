<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TechnicalReportPicture extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $appends = ['full_url'];

    public function getFullUrlAttribute(){
        return  $this->picture ? url($this->picture) : asset('images/default/default.jpg');
    }

}
