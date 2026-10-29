<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class UserWorkingHour extends Model
{

    use softDeletes;
    protected $guarded = ['id'];

    public function availabilities(){
        return $this->belongsToMany(Availability::class, 'ava_use_wor_hou');
    }

}
