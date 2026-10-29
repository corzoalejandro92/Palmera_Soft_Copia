<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmScheduleStatus extends Model
{
    use softDeletes;
    protected $guarded = ['id'];
    protected $table = 'crm_schedule_status';
    protected $appends = ['text_color'];

    public function getTextColorAttribute(){
        return \App\General\Contrast::generate($this->color);
    }
}
