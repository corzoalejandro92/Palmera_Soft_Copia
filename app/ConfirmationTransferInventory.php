<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfirmationTransferInventory extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function detailConfirmationTranferInventory ()
    {
       return $this->hasMany(DetailConfirmationTransfer::class,'confirmation_transfer_id');
    }
}
