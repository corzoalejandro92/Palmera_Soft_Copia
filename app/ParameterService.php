<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class ParameterService extends Model
{
    use SoftDeletes, GeneralScope;

    protected $guarded = ['id'];

    public function Service ()
    {
       return $this->belongsTo(Service::class);
    }

    public function headquarters(){
        return $this->belongsToMany(Headquarter::class);
     }


}
