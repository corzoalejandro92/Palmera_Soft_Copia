<?php

namespace App\General;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\CrmTrackingTypes;
use App\CrmTracking;
use App\Quote;
use Exception;



trait CrmTrackings
{
    public static $error = "";
    
    public static function report(Request $request, $model, $case, $action = 'create', $user = null){
        switch ($case) {
            case 'quote':
                    if($action == 'update'){
                        $model->crmTracking()->create([
                            "title" => Auth::user()->name." ha modificado la cotización #".$model->id,
                            "detail" => "Se han realizado modificaciones a esta cotización. Esta novedad se genera automáticamente cuando un asesor edita una cotización.",
                            "date" => Carbon::now()->format('Y-m-d H:i:s'),
                            "user_id" => Auth::user()->id,
                            "crm_id" => $model->crm_id,
                            "office_id" => isset($model->office_id) ? $model->office_id : null,
                            "headquarter_id" => session('headquarter_id'),
                            "crm_tracking_type_id" => CrmTrackingTypes::where('slug','quote')->get()->first()->id,
                        ]);
                    }else if($action == 'create'){
                        $model->crmTracking()->create([
                            "title" => Auth::user()->name." ha generado la cotización #".$model->id,
                            "detail" => "Se ha creado una cotización nueva con estado pendiente .Esta novedad se genera automáticamente cuando un asesor genera una nueva cotización.",
                            "date" => Carbon::now()->format('Y-m-d H:i:s'),
                            "user_id" => Auth::user()->id,
                            "crm_id" => $model->crm_id,
                            "office_id" => isset($model->office_id) ? $model->office_id : null,
                            "headquarter_id" => session('headquarter_id'),
                            "crm_tracking_type_id" => CrmTrackingTypes::where('slug','quote')->get()->first()->id,
                        ]);
                    }
                break;
                case 'event':
                    
                    $model->crmTracking()->create([
                        "title" => Auth::user()->name." ha añadido un evento a la agenda",
                        "detail" => "Se ha creado un evento en la agenda llamado '".$model->name."' desde " . Carbon::parse($model->start)->format('d/m/Y') . ' a las '. Carbon::parse($model->start)->format('h:i a')." hasta " . Carbon::parse($model->end)->format('d/m/Y') . ' a las '. Carbon::parse($model->end)->format('h:i a'),
                        "date" => Carbon::now()->format('Y-m-d H:i:s'),
                        "user_id" => Auth::user()->id,
                        "crm_id" => $model->crm_id,
                        "headquarter_id" => session('headquarter_id'),
                        "crm_tracking_type_id" => CrmTrackingTypes::where('slug','event')->get()->first()->id,
                    ]);
                    
                break;

                case 'reminder':
                    
                    $model->crmTracking()->create([
                        "title" => Auth::user()->name." ha añadido un recordatorio a la agenda",
                        "detail" => "Se ha creado un recordatorio en la agenda llamado '".$model->name."' para el " . Carbon::parse($model->start)->format('d/m/Y') . ' a las '. Carbon::parse($model->start)->format('h:i a'),
                        "date" => Carbon::now()->format('Y-m-d H:i:s'),
                        "user_id" => Auth::user()->id,
                        "crm_id" => $model->crm_id,
                        "headquarter_id" => session('headquarter_id'),
                        "crm_tracking_type_id" => CrmTrackingTypes::where('slug','reminder')->get()->first()->id,
                    ]);
                    
                break;

                case 'status':
                $model->crmTracking()->create([
                        "title" => ($user == null ? Auth::user()->name: $user->name) ." ha cambiado el estado de la cotización #".$model->code,
                        "detail" => "Se ha cambiado el estado de esta cotización a  {$model->status->name}. Esta novedad se genera automáticamente cuando un usuario del sistema (incluyendo el cliente) cambia el estado de alguna cotización.",
                        "date" => Carbon::now()->format('Y-m-d H:i:s'),
                        "user_id" => ($user == null ? Auth::user()->id: $user->id),
                        "crm_id" => $model->crm_id,
                        "office_id" => $model->office_id,
                        "headquarter_id" => ($user == null ? session('headquarter_id'): $user->headquarter_id),
                        "crm_tracking_type_id" => CrmTrackingTypes::where('slug','quote')->get()->first()->id,
                    ]);
                break;
            case 'contract':
                $model->crmTracking()->create([
                        "title" => Auth::user()->name." ha creado un contrato para la cotización #".$model->id,
                        "detail" => "Se ha creado un registro de contrato para esta cotización, con vigencia del ".Carbon::parse($model->contract->start_date)->format('d/m/Y')." al ".Carbon::parse($model->contract->end_date)->format('d/m/Y').". Esta novedad se genera automáticamente cuando un usuario del sistema crea un contrato desde el módulo de cotización.",
                        "date" => Carbon::now()->format('Y-m-d H:i:s'),
                        "user_id" => Auth::user()->id,
                        "crm_id" => $model->crm_id,
                        "headquarter_id" => session('headquarter_id'),
                        "crm_tracking_type_id" => CrmTrackingTypes::where('slug','contract')->get()->first()->id,
                    ]);
                break;
            case 'schedule':
                if($request->filled('quote')){
                    $title          = Auth::user()->name." ha generedo/actualizado el cronograma de servicio de la cotización #".  Quote::find($request->input('id'))->consecutive;
                    $description    = "Se ha generado/actualizado el cronograma de servicio a partir de la cotización #". Quote::find($request->input('id'))->consecutive .". Esta novedad se genera automáticamente cuando un usuario gestiona el cronograma de actividades";

                }else{
                    $title          = Auth::user()->name." ha generedo/actualizado un cronograma de servicio manual.";
                    $description    = "Se ha generado/actualizado el cronograma de servicio manual. Esta novedad se genera automáticamente cuando un usuario gestiona el cronograma de actividades";

                }

                $model->crmTracking()->create([
                    "title" => $title,
                    "detail" => "{$description}.",
                    "date" => Carbon::now()->format('Y-m-d H:i:s'),
                    "user_id" => Auth::user()->id,
                    "crm_id" => $model->crm_id,
                    "headquarter_id" => session('headquarter_id'),
                    "crm_tracking_type_id" => CrmTrackingTypes::where('slug','schedule')->get()->first()->id,
                ]);

                break;
            
                default:
                # code...
                break;
        }
    }

}

