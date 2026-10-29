<?php

namespace App;

use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Periodicity extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = ['id'];
}
