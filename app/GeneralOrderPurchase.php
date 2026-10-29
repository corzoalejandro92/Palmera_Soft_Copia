<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralOrderPurchase extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function detailGeneralOrderPurchases ()
    {
       return $this->hasMany(DetailGeneralOrderPurchase::class);
    }
}
