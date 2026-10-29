<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailConfirmationTransfer extends Model
{
    protected $guarded = [];
    public function confirmationTransferInventory ()
    {
       return $this->belongsTo(ConfirmationTransferInventory::class,'confirmation_transfer_id');
    }
}
