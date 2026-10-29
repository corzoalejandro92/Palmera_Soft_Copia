<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class OrderPurchase extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = [];

    public function detailOrderPurchases ()
    {
       return $this->hasMany(OrderPurchaseDetail::class);
    }

    public function warehouse ()
    {
        return $this->belongsTo (Warehouse::class);
    }

    public function product()
    {
       return $this->belongsTo(Product::class, 'product_id');
    }
}
