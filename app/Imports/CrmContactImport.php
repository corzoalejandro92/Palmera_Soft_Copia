<?php

namespace App\Imports;

use App\Contact;
use App\ContactType;
use App\Crm;
use App\CrmType;
use App\Exceptions\ImportDataException;
use App\MeanContact;
use App\Office;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CrmContactImport implements ToCollection

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
            $contact =  $this->createContact($index, $row);
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


    private function createContact($index, $row){
        if($this->getStringValue($row[0])!= ''){

        if($this->getStringValue($row[4] == '')){

            //tratar de tomar la primer oficina del cliente
            if($this->getStringValue($row[5] !='')){
                $crm = Crm::where('name', 'like', '%'.$this->getStringValue($row[5]).'%')->first();
                if(isset($crm)){
                    $office = $crm->offices()->first();
                }
                
            }else{
                $alphabet = range('A', 'Z');
                throw new ImportDataException("[CRM Contactos][".($index+1)."$alphabet[0]]: (1) El contacto $row[0]  no tiene una sucursal de cliente válida o existente: $row[4]", 406);
            }
        }else{
            $office = Office::where('name', 'like', '%'.$this->getStringValue($row[4]).'%')->first();
        }

        if(!isset($office)){
            $alphabet = range('A', 'Z');
            throw new ImportDataException("[CRM Contactos][".($index+1)."$alphabet[0]]: (2) El contacto $row[0]  no tiene una sucursal de cliente válida o existente: $row[4]", 406);
        }

            $contact     = Contact::updateOrCreate(
                [
                    "name" => $this->getStringValue($row[0])
                ],
                [
                    "phone"                 => $this->getIntValue($row[1]),
                    "position"              => trim($row[3]),
                    "email"                 => trim($row[2]),
                ]);
    
                $contact->offices()->sync([$office->id]);
                $contact->types()->sync([1,2,3]);
    
                return $contact;
     }
        
    }

    
}
