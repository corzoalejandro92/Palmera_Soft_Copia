<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RouteStatus extends Model
{

    use SoftDeletes;
    protected $table = 'route_status';
    protected $guarded = ['id'];
    
}
