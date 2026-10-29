<?php

namespace App;

use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Date\Date;

class CrmTracking extends Model
{
    use SoftDeletes,GeneralScope;

    protected $guarded = ['id'];

    public function trackable()
    {
        return $this->morphTo();
    }

    public function commercial(){
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function crmTrackingType(){
        return $this->belongsTo(CrmTrackingTypes::class, 'crm_tracking_type_id');
    }
    public function getDateAttribute($date)
    {
        return new Date($date);
    }
}

