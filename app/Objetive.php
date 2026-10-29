<?php

namespace App;

use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\Model;

class Objetive extends Model
{
    use GeneralScope;
    protected $guarded = ['id'];

    public function type(){
        return $this->belongsTo(ObjetiveType::class,'type_objetive_id');
    }
}
