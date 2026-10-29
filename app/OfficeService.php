<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OfficeService extends Pivot
{
    protected $casts = ['technicals' => 'array'];
}
