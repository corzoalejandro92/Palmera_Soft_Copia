<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class SheetsManager implements WithMultipleSheets

{
    protected $sheetRequest = null;

    public function __construct($sheetRequest)
    {
        $this->sheetRequest = $sheetRequest;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function sheets(): array
    {
        
        $toImport = [];
        foreach ($this->sheetRequest as $key => $sheet) {
            switch ($sheet) {
                case 'CRM':
                    $toImport[$sheet] = new CrmSheetImport(); 
                    break;
                
                case 'CRM Sucursales':
                    $toImport[$sheet] = new CrmOfficeSheetImport(); 
                    break;

                case 'CRM Contactos':
                    $toImport[$sheet] = new CrmContactImport(); 
                    break;

                case 'Servicios':
                    $toImport[$sheet] = new ServiceSheetImport(); 
                    break;

                case 'Tarifa de servicios x sedes':
                    $toImport[$sheet] = new ServicePriceSheetImport(); 
                    break;
            }
        }

        set_time_limit(300);

        return $toImport;  
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("La Hoja  {$sheetName} dentro de este excel no será tenidas en cuenta");
    }
}
