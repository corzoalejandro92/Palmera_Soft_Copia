<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\General\DateTranslator;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class QuoteTemplate extends Model
{
    use GeneralScope, DateTranslator, SoftDeletes;

    protected $guarded = ['id'];

    public function headquarters(){
        return $this->belongsToMany(Headquarter::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
