<?php

namespace App\Services;

use App\CrmExternalNote;
use App\FileExtend;
use App\Product;
use App\Scheduling;
use App\TechnicalReportType;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExternalNoteService{
    public function new(Request $request, Scheduling $scheduling, User $user){

        $productsName = '';
        $products = Product::whereIn('id', $request->input('reported_products'))->get();
        if($products->count() > 0){
            $productsName = $products->pluck('name')->join(', ');
        }

        $mechanism = FileExtend::withTrashed()->find($request->input('file_extend_id'));

        $mechanismName  = $mechanism->extendible->slug.'#' .$mechanism->num;

        $reportTypeName = TechnicalReportType::find($request->input('technical_report_type_id'))->name;

        $note = CrmExternalNote::create([
            "crm_id" => $scheduling->crm_id,
            "office_id" => $scheduling->office_id,
            "user_id" => $user->id,
            "date" => Carbon::now()->format('Y-m-d'),
            "type" => 2,
            "observation" =>  '[ESTACIÓN '.strtoupper($mechanismName) .',  TRAMPA(S) REPORTADA(S) COMO '.$reportTypeName.' : '.$productsName.'] - ' .$request->input('observation')
        ]);

        return $note;
    }
}