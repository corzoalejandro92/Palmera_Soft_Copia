<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $appends=['model_name','eventt','title'];
    
    public function headquarter(){
        return $this->belongsTo(Headquarter::class);
    }
    public function user(){
        return $this->belongsTo(User::class)->select(['users.id','users.name']);
    }
    public function users(){
        return $this->belongsTo(User::class,'auditable_id')->withTrashed();
    }
    public function crms(){
        return $this->belongsTo(Crm::class,'auditable_id')->withTrashed();
    }
    public function headquarters(){
        return $this->belongsTo(Headquarter::class,'auditable_id')->withTrashed();
    }
    public function offices(){
        return $this->belongsTo(Office::class,'auditable_id')->withTrashed();
    }
    public function availabilities(){
        return $this->belongsTo(Availability::class,'auditable_id')->withTrashed();
    }
    public function routes(){
        return $this->belongsTo(Route::class,'auditable_id')->withTrashed();
    }
    public function automations(){
        return $this->belongsTo(Automation::class,'auditable_id')->with(['user'])->withTrashed();
    }
    public function crmSchedules(){
        return $this->belongsTo(CrmSchedule::class,'auditable_id')->withTrashed();
    }
    public function crmScheduleControl(){
        return $this->belongsTo(CrmScheduleControl::class,'auditable_id')->withTrashed();
    }
    public function quote(){
        return $this->belongsTo(Quote::class,'auditable_id')->withTrashed();
    }

    public function getModelNameAttribute(){
        $modelExploded=explode('\\',$this->auditable_type);
        $modelName=$modelExploded[count($modelExploded)-1];
        if($modelName == "User") $modelName="Usuario";
        if($modelName == "Crm") $modelName="Crm / Cliente";
        if($modelName == "Office") $modelName="Sucursal";
        if($modelName == "Headquarter") $modelName="Sede";
        if($modelName == "Availability") $modelName="Disponibilidad";
        if($modelName == "Route") $modelName="Ruta";
        if($modelName == "CrmSchedule") $modelName="Cronograma de Actividades";
        if($modelName == "Automation") $modelName="Automatización";
        if($modelName == "CrmScheduleControl") $modelName="Control de Servicio";
        if($modelName == "Quote") $modelName="Cotización";
        return $modelName;
    }

    public function getTitleAttribute(){
        $modelExploded=explode('\\',$this->auditable_type);
        $modelName=$modelExploded[count($modelExploded)-1];
        $title="";

        if($modelName == "Crm"){
            $model=$this->belongsTo(Crm::class,'auditable_id')->select(['name'])->withTrashed()->get();
            $title=$model[0]->name;
        }

        if($modelName == "User"){
            $model=$this->belongsTo(User::class,'auditable_id')->select(['name'])->withTrashed()->get();
            $title=$model[0]->name;
        }

        if($modelName == "Headquarter"){
            $model=$this->belongsTo(Headquarter::class,'auditable_id')->select(['name'])->withTrashed()->get();
            $title=$model[0]->name;
        }

        if($modelName == "Office"){
            $model=$this->belongsTo(Office::class,'auditable_id')->withTrashed()->get();
            $title=$model[0]->name." ".(isset($model[0]->crm->name) ? ' / '.$model[0]->crm->name : '');
        }
        
        if($modelName == "Availability"){
            $model=$this->belongsTo(Availability::class,'auditable_id')->select(['name'])->withTrashed()->get();
            $title=$model[0]->name;
        }

        if($modelName == "Route"){
            $model=$this->belongsTo(Route::class,'auditable_id')->select(['user_id'])->withTrashed()->get();
            if(isset($model[0]->user_id))
                $user=User::select(['name'])->where('id',$model[0]->user_id)->withTrashed()->get();
                $title=$user[0]->name;

        }
        if($modelName == "Automation"){
            $model1=$this->belongsTo(Automation::class,'auditable_id')->with(['user'])->withTrashed()->get();
            $title = $model1[0]->user->name;

        }

        if($modelName == "CrmSchedule"){
            $model=$this->belongsTo(CrmSchedule::class,'auditable_id')->select(['crm_id','office_id'])->withTrashed()->get();
            $crm='';
            $office='';
            if(isset($model[0]->crm_id)) $crm=Crm::select(['name'])->where('id',$model[0]->crm_id)->withTrashed()->get();
            if(isset($model[0]->office_id)) $office=Office::select(['name'])->where('id',$model[0]->office_id)->withTrashed()->get();

            $title = isset($crm[0]->name)?$crm[0]->name : '';
            $title .= isset($office[0]->name)? " - ".$office[0]->name:'';
        }

        if($modelName == "CrmScheduleControl"){
            $model=$this->belongsTo(CrmScheduleControl::class,'auditable_id')->withTrashed()->get();
            $title=$model[0]->infestation->name. " - ".$model[0]->service->crm->name." ( ".$model[0]->service->office->name." )";

        }
        if($modelName == "Quote"){
            $model=$this->belongsTo(Quote::class,'auditable_id')->withTrashed()->get();
            $title=$model[0]->crm->name. " - ".$model[0]->office->name;
        }
        return $title;
    }
    public function getEventtAttribute(){
        $event=$this->event;
        if($event == "created") $event="Crear";
        if($event == "deleted") $event="Eliminar";
        if($event == "updated") $event="Actualizar";
        if($event == "restored") $event="Restaurar";
        return $event;
    }
}
