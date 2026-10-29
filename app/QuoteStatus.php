<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class QuoteStatus extends Model
{

	use SoftDeletes, GeneralScope;

    protected $fillable= ['id'];
    protected $table= 'quote_status';
}
