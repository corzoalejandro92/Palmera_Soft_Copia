<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use GeneralScope, SoftDeletes;

    protected $guarded=[];
    
    public function headquarter(){
       return $this->belongsTo(Headquarter::class,'headquarter_id'); 
    }

    public function office()
    {
       return $this->hasOne(Office::class);
    }

    public function users(){
        return $this->belongsToMany(User::class,'user_zone');
    }
}
