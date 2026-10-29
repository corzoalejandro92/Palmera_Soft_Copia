<?php

namespace App\Imports;


use App\Crm;
use App\CrmType;
use App\Exceptions\ImportDataException;
use App\Headquarter;
use App\MeanContact;
use App\Service;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class ServiceSheetImport implements ToCollection, SkipsOnFailure

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
            $this->createService($row);
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


    private function createService($row){

        $service     = Service::updateOrCreate(
            [
                "name" => $this->getStringValue($row[0])
            ],
            [
                "description"           => $this->getIntValue($row[1]) == '' ? 'Sin descripción' : $this->getIntValue($row[1]),
                "avatar"                => 'custom-default',
            ]);        

            return $service;
    }


public function onFailure(Failure ...$failures)
{
    // Handle the failures how you'd like.
}
    
}
