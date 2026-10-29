<?php

namespace App;

use App\Scopes\GeneralScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Date\Date;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class CrmSchedule extends Model implements Auditable
{
    use GeneralScope;
    use SoftDeletes;
    use AuditingAuditable;
    protected $guarded = ['id'];
    protected $appends = ['datec', 'duration', 'duration_text', 'url_to_edit', 'month_text'];
    protected $casts = ['week_data' => 'object', 'weeks' => 'array', 'technicals' => 'array'];

    public static $rel_users = null;

    public function workschedule()
    {
        return $this->belongsTo(Workschedule::class, 'workschedule_id');
    }

    public function infestationGrades(){
        return $this->hasMany(InfestationGrade::class, 'crm_schedule_id');
    }

    public function consumptions(){
        return $this->hasMany(Consumption::class, 'crm_schedule_id');
    }

    public function reportedProducts(){
        return $this->hasMany(ReportedProduct::class, 'crm_schedule_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id')->withTrashed();
    }

    public function controls()
    {
        return $this->hasMany(CrmScheduleControl::class)->orderBy('id');
    }

    public function control()
    {
        return $this->hasOne(CrmScheduleControl::class)->orderBy('id');
    }

    public function coverage()
    {
        return $this->belongsTo(DetailService::class, 'coverage_id')->withTrashed();
    }

    public function inventoryExpense()
    {
        return $this->hasMany(InventoryExpense::class, 'crm_schedule_id');
    }

    public function status()
    {
        return $this->belongsTo(CrmScheduleStatus::class, 'status_id')->withTrashed();
    }

    public function timeUnit()
    {
        return $this->belongsTo(TimeUnit::class, 'time_unit_id');
    }

    public function scheduling()
    {
        return $this->hasOne(Scheduling::class)->orderBy('created_at', 'desc');
    }

    public function schedulings()
    {
        return $this->hasMany(Scheduling::class);
    }

    public function crm()
    {
        return $this->belongsTo(Crm::class, 'crm_id')->withTrashed();
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id')->withTrashed();
    }

    public function answersSurveys()
    {
        return $this->hasMany(AnswersSurveys::class, 'crm_schedule_id')->withTrashed();
    }

    public function observations()
    {
        return $this->hasMany(CrmScheduleObservation::class);
    }

    public function crmTracking()
    {
        return $this->morphOne(CrmTracking::class, 'trackable');
    }

    public function crmTrackings()
    {
        return $this->morphMany(CrmTracking::class, 'trackable');
    }

    public function technicalReports()
    {
        return $this->hasMany(TechnicalReport::class);
    }

    public function userTrackings()
    {
        return $this->morphMany(UserTracking::class, 'trackable');
    }

    public function technical_users()
    {
        return $this->hasMany(User::class)->withTrashed()->setQuery(
            User::whereIn('id', ($this->attributes['technicals'] ?? false) ? json_decode($this->attributes['technicals']) : [])->getQuery()
        );
    }

    public function technical_users_files()
    {
        return $this->technical_users()->with(['files']);
    }

    public function getDurationAttribute()
    {
        if ($this->timeUnit) {
            return $this->time_value * $this->timeUnit->seconds;
        }

        return 0;
    }

    public function getDurationTextAttribute()
    {
        if ($this->timeUnit) {
            return $this->time_value . ' ' . $this->timeUnit->name;
        }

        return 0;
    }

    public function getDatecAttribute()
    {
        return new Date($this->date);
    }

    public function getMonthTextAttribute()
    {
        return ucfirst(\Jenssegers\Date\Date::parse($this->date)->format('F')) . '/' . Carbon::parse($this->date)->format('Y');
    }

    public function getNumAttribute($item)
    {
        if (is_null($item)) {
            $item = 1;
        }

        return $item;
    }

    public function getRawTechnicalAttribute()
    {
        return json_decode($this->getOriginal('technicals') ?? "[]");
    }

    public function getTechnicalsAttribute()
    {
        // Optimización CrmController@_getSchedulesCalendar
        if (self::$rel_users) {
            return collect($this->raw_technical)->map(function ($id) {
                return self::$rel_users[$id];
            });
        }
        return $this->technical_users_files;
    }

    public function getUrlToEditAttribute()
    {
        /* $quote = $this->quote()->get()->first(); */

        return route('crm.edit-workschedule', ['crm' => $this->crm_id, 'workschedule' => $this->workschedule_id, 'office' => $this->office_id]) . '?from=' . ($this->original_date ?? $this->date) . '#tabSchedule';
    }
}
