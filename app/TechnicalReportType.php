<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TechnicalReportType extends Model
{
    use SoftDeletes;
    protected $filled = ['id'];

    const NOT_FOUND = 4;
    const DAMAGED = 16;
    const REPLACE = 17;

    public function parent(){
    	return $this->belongsTo(TechnicalReportType::class, 'parent_id');
    }

    public function childs(){
    	return $this->hasMany(TechnicalReportType::class, 'parent_id');
    }
}
