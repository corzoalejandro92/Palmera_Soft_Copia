<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileTypeCustomer extends Model
{
    protected $table = 'file_type_customers';

    public function documentcustomers ()
    {
       return $this->hasOne(DocumentCustomer::class);
    }
}
