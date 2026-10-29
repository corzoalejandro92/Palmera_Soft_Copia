<?php

namespace App;

use App\General\DateTranslator;
use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes,GeneralScope,DateTranslator;

    protected $guarded = ['id'];

    public function quote(){
        return $this->belongsTo(Quote::class, 'quote_id');
    }

}
