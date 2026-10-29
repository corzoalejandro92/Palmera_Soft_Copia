<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class Application extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = ['id'];

    public function productos ()
    {
        return $this->hasMany(Product::class,'id');
    }

    public function quoteSerInfProd(){
        return $this->morphOne(File::class, 'productable');
    }
  
    public function quoteSerInfProds(){
        return $this->morphMany(File::class, 'productable');
    }

    function user(){
        return $this->belongsTo(User::class,'updated_user');
     }

}
