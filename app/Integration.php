<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Integration extends Model
{
    use SoftDeletes;

    const FOXCON = 1;

    protected $casts = ['headquarters' => 'array'];
}
