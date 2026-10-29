<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileTypeVehicle extends Model
{
    protected $table = 'file_type_vehicles';

    public function vehicles ()
    {
       return $this->hasOne(DocumentVehicle::class);
    }
}
