<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\GeneralScope;

class PaperSize extends Model
{
    use GeneralScope;
    protected $guarded = ["id"];
}
