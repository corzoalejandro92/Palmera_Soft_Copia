<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class Contact extends Model
{
    use SoftDeletes, GeneralScope;
    
    protected $guarded = ['id'];

    public function offices ()
    {
        return $this->belongsToMany(Office::class,'contact_office');
    }

    public function crm(){
        return $this->belongsTo(Crm::class, 'crm_id')->withTrashed();
    }

    public function types()
    {
       return $this->belongsToMany (ContactType::class,'contact_contact_type');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
