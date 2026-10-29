<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketAnswer extends Model
{
    protected $guarded = ['id'];
    
    public function registered()
    {
        return $this->belongsTo(User::class,'registered_by');
    }
}
