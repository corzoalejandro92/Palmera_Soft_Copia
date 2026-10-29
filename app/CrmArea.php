<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class CrmArea extends Model
{
    use SoftDeletes, GeneralScope;

    protected $guarded=['id'];

   function user(){
      return $this->belongsTo(User::class,'updated_user');
   }
    
}
