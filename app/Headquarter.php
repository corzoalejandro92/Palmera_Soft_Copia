<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;
class Headquarter extends Model implements Auditable
{
    use SoftDeletes, AuditingAuditable;
    protected $guarded = ['id'];


    public function zones(){
        
    return $this->hasMany(Zone::class);
        
    }

    public function scheduling ()
    {
        return $this->hasMany(Scheduling::class);
    }
    
    public function users(){
        return $this->hasOne(User::class);
    }
    
    public function warehouse(){
        return $this->morphOne(Warehouse::class, 'warehousable');
    }
    
    public function cities(){
        return $this->belongsTo(City::class,'city_id');
    }

    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }

    public function purchases ()
    {
        return $this->hasMany(Purchase::class,'headquarter_id');
    }

    public function transferInventorys ()
    {
        return $this->hasMany(TransferInventory::class,'headquarter_id');
    }

    public function offices (){
        return $this->hasMany(Office::class, 'headquarter_id');
    }

}
