<?php


namespace App\General;

use Jenssegers\Date\Date;
trait DateTranslator
{
    public function getCreatedAtAttribute($created_at){
        return new Date($created_at);
    }

    public function getUpdatedAtAttribute($updated_at){
        return new Date($updated_at);
    }

    public function getDeletedAtAttribute($deleted_at){
        return new Date($deleted_at);
    }

}