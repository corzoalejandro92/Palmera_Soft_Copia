<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    protected $guarded = ["id"];

    public function contact ()
    {
       return $this->belongsTo(Contact::class, 'mean_contact_id');
    }
}
