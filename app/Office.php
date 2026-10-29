<?php

namespace App;

use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Office extends Model implements Auditable
{
    use SoftDeletes;
    use GeneralScope;
    use AuditingAuditable;
    protected $guarded = ['id'];
    protected $with = ['city.state'];
    protected $appends = ['full_address', 'avatar', 'schedulecontact'];

    public function crm()
    {
        return $this->belongsTo(Crm::class);
    }

    public function externalNotes()
    {
        return $this->hasMany(CrmExternalNote::class);
    }

    public function ticketQuestions()
    {
        return $this->hasMany(TicketQuestion::class, 'office_id');
    }

    public function schedules()
    {
        return $this->hasMany(CrmSchedule::class);
    }

    public function officeTracking()
    {
        return $this->morphOne(CrmTracking::class, 'trackable');
    }

    public function cities()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'office_user');
    }

    public function quotes()
    {
        return $this->belongsTo(Quote::class);
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_office');
    }

    public function schedule_contacts()
    {
        return $this->contacts()->whereHas('types', function ($types) {
            $types->where('contact_types.id', '3'); 
        });
    }

    public function schedule_contact()
    {
        return $this->belongsToMany(Contact::class, 'contact_office');
    }

    public function warehouse()
    {
        return $this->morphOne(Warehouse::class, 'warehousable');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'filable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'filable')->orderBy('id', 'desc');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)->withTimestamps()->withPivot(['technical_quantity', 'time', 'technicals', 'start', 'end', 'init_status'])->using(OfficeService::class);
    }

    public function workschedules()
    {
        return $this->hasMany(Workschedule::class);
    }

    public function areas()
    {
        return $this->hasMany(AreaOffice::class);
    }

    public function getFullAddressAttribute()
    {
        return $this->address.', '.(isset($this->city->name) ? (($this->city->name ?? '').', '.(isset($this->city->state) && $this->city->state->name ? $this->city->state->name : '').'.') : '');
    }

    public function getAvatarAttribute()
    {
        return url('/images/default/office.png');
    }

    public function getSchedulecontactAttribute()
    {
        return $this->schedule_contacts->first();
    }
}
