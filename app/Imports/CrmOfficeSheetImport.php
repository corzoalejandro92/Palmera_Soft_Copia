<?php

namespace App\Imports;

use App\City;
use App\ContactType;
use App\Crm;
use App\CrmType;
use App\Exceptions\ImportDataException;
use App\Headquarter;
use  App\General\Warehouses;
use App\Office;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class CrmOfficeSheetImport implements ToCollection

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
            $office = $this->createOfficeCustomer($row);
        
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


    private function createOfficeCustomer($row){

        //consultar el cliente
        $crms  = Crm::where('name', 'like', '%'.$this->getStringValue($row[5]).'%')->get();
        if($crms->count() == 0){
            throw new ImportDataException("La sucursal $row[0] ($row[1]) no tiene un cliente válido o existente: $row[5]", 406);
        }

        $crm = $crms->last();

        //consultar la sede
        $headquarter =  Headquarter::find($this->getIntValue($row['4']));
        
        if(is_null($headquarter)){
            throw new ImportDataException("La sede $row[4], específicada en la sucursal $row[0] ($row[1]) del cliente $row[5] no existe en el sistema", 406);
        }


        //consultar el id de la ciudad (si se encuentra, sino, se pondrá el de la sede)
        $cities        = City::where('name','like','%'.$row[3].'%')->get();
        if($cities->count() == 0){
            $city_id = $headquarter->city_id;
        }
        $city_id        = $cities->last()->id;

        //consultar las coordenadas del la direcciónd el cliente
        $coords = $this->tryGetCoordOfAddress($row[1] . " " .$row[3]);

        $office     = Office::updateOrCreate(
            [
                "name"   => $this->getStringValue($row[0]),
                "crm_id" => $crm->id,
                "headquarter_id"        => $headquarter->id,
            ],
            [
                "address"               => trim($row[1]),
                "physical_address"      => trim($row[1]),
                "observation"           => trim($row[2]),
                "city_id"               => $city_id,
                "lat"                   => $coords[0],
                "lng"                   => $coords[1],
            ]);

            //crear-actualizar bodega de tipo cliente
            Warehouses::setWarehouse($office,$headquarter->id);     

            return $office;
    }

    private function tryGetCoordOfAddress($address){

        $address = urlencode($address);
        $url = "https://maps.google.com/maps/api/geocode/json?key=".config('google.maps.api_key')."&sensor=false&address=" . $address;
        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);
        
        
        
    
        if ( in_array($json['status'],['REQUEST_DENIED'])) {
            
            throw new ImportDataException("Error emitido desde Google Maps: ".$json['error_message'], 406);
            
        }

        if ( in_array($json['status'],['ZERO_RESULTS'])) {
            
            return [];
            
        }
    

        $lat = $json['results'][0]['geometry']['location']['lat'];
        $lng = $json['results'][0]['geometry']['location']['lng'];
        
        return array($lat, $lng);
    }
    
}
