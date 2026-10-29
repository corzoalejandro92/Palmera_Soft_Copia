<?php

namespace App\Imports;


use App\Crm;
use App\CrmType;
use App\Exceptions\ImportDataException;
use App\Headquarter;
use App\MeanContact;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class CrmSheetImport implements ToCollection, SkipsOnFailure

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
            $this->createCustomer($row);
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


    private function createCustomer($row){

        $crmType        = CrmType::firstOrCreate(["name" => $this->getStringValue($row[3])]);
        $meanContact    = MeanContact::firstOrCreate(["name" => $this->getStringValue($row[4])]);


        $crm     = Crm::updateOrCreate(
            [
                "name" => $this->getStringValue($row[0])
            ],
            [
                "type_customer"         => $this->getIntValue($row[1]) == '' ? 0 : $this->getIntValue($row[1]),
                "document"              => trim($row[2]),
                "crm_type_id"           => $crmType->id,
                "mean_contact_id"       => $meanContact->id,
                "customer_national"     => $this->getIntValue($row[5]),
                "enabled"               => $this->getIntValue($row[6]) == '' ? 1 : $this->getIntValue($row[6]),
            ]);

        if(trim($row[7]) != ''){
            //crear una sucursal del cliente forzosamente

            //consultar la sede
            $headquarter =  Headquarter::find($this->getIntValue($row['7']));
            if(is_null($headquarter)){
                throw new ImportDataException("La sede $row[4], específicada en el cliente $row[0] no existe en el sistema", 406);
            }


            $crm->offices()->create([
                "name"              => "Principal",
                "city_id"           => $headquarter->city_id,
                "address"           => 'Sin Especificar',
                "headquarter_id"    => $headquarter->id

            ]);

        }

            return $crm;
    }


public function onFailure(Failure ...$failures)
{
    // Handle the failures how you'd like.
}
    
}
