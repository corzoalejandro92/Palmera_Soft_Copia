<?php

namespace App\Imports;


use App\Crm;
use App\CrmType;
use App\DetailService;
use App\Exceptions\ImportDataException;
use App\Headquarter;
use App\MeanContact;
use App\Service;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class ServicePriceSheetImport implements ToCollection, SkipsOnFailure

{
    /**
    * @param array $row
    *
    * @return Illuminate\Support\Collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) 
        {
            if($index == 0 || $row->filter()->isEmpty()) continue;
            $this->createService($index, $row);
        }

    }

    private function getStringValue($cell){
        $explodedString   = explode('-', trim($cell));
        $explodedString  = end($explodedString);
        return $explodedString;
    }

    private function getIntValue($cell){
        $explodedString   = explode('-', trim($cell));
        $explodedString  = reset($explodedString);
        return $explodedString;
    }


    private function createService($index, $row){

        //encontrar al servicio padre
        if($this->getStringValue($row[0]) != ''){

            $service = Service::where('name','like','%'.$this->getStringValue($row[0]).'%')->first();
            if(!isset($service)){
                $alphabet = range('A', 'Z');
                throw new ImportDataException("[Tarifa de servicios x sedes][".($index+1)."$alphabet[0]]: (1) El servicio $row[0] de $row[2] - $row[1]  no es válido o ya no se encuetra disponible", 406);
            }
        }

        //consultar la sede
        $headquarter =  Headquarter::find($this->getIntValue($row['1']));
        
        if(is_null($headquarter)){
            $alphabet = range('A', 'Z');
            throw new ImportDataException("[Tarifa de servicios x sedes][".($index+1)."$alphabet[1]]: La sede del registro no existe en el sistema", 406);
        }


        $dservice     = DetailService::firstOrCreate(
            [
                "service_id"        => $service->id,
                "zone"              => trim($row[2]),
                "cost"             => trim($row[3])
            ],
            [
                "visits"            => trim($row[4]),
            ]);

            $result = $dservice->headquarters()->syncWithoutDetaching([$headquarter->id]);

            return $service;
    }


public function onFailure(Failure ...$failures)
{
    // Handle the failures how you'd like.
}
    
}
