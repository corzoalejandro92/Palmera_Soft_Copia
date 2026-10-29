<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\General\DateTranslator;

class CrmExternalNote extends Model
{

    use softDeletes, DateTranslator;

    protected $guarded  = ['id'];

    protected $appends = ['value_format'];

    public function office(){
        return $this->belongsTo(Office::class,'office_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function getValueFormatAttribute(){
        return number_format($this->value);
    }
}
