<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    public function detailBillings ()
    {
       return $this->hasMany(BillingDetail::class);
    }

    public function crm(){
        return $this->belongsTo(Crm::class, 'crm_id');
   }


   public function billingState ()
   {
      return $this->belongsTo(BillingState::class,'billing_state_id');
   }

}
