<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class MethodProduct extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = ["id"];
}
