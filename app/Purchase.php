<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class Purchase extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = [];

    public function detailPurchases()
    {
       return $this->hasMany(PurchaseProductDetails::class);
    }

    public function headquarter ()
    {
        return $this->belongsTo (Headquarter::class);
    }

    public function warehouse ()
    {
        return $this->belongsTo (Warehouse::class,'warehouse_id');
    }

   
}
