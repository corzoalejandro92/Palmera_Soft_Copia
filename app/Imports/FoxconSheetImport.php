<?php

namespace App\Imports;


use App\Crm;
use App\CrmType;
use App\Exceptions\ImportDataException;
use App\Headquarter;
use App\Inventory;
use App\MeanContact;
use App\Models\Api\Product;
use App\Warehouse;
use DateTime;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Validators\Failure;

class FoxconSheetImport implements ToModel, SkipsOnFailure, WithChunkReading

{

    public $missingProducts = [];
    public $missingWarehouses = [];

    private $isFirstRow = true;
    /**
    * @param array $row
    *
    */
    public function model(array $row)
    {
    
        Log::channel('foxcon-import')->info('Procesando fila: '. $row[0] . ' - '. $row[1] . ' - '. $row[2] . ' - '. $row[3] . ' - '. $row[4] . ' - '. $row[5] . ' - '. $row[6] . ' - '. $row[7]);
        if(!empty($row) && !$this->isFirstRow){
            Log::channel('foxcon-import')->info('-------------------- Fila llena');
            $this->inventory($row);
        }else{
            $this->isFirstRow = false;
            Log::channel('foxcon-import')->info('-------------------- Fila vacia');
        }

        
        if(count($this->missingProducts) > 0){
            $productsLbl = '';
            foreach ($this->missingProducts as $key => $product) {
                $productsLbl.= 'No existe el producto '.$product['code']. ' - '.$product['name']." en el soft de palmera junior<br>";
            }
            throw new ImportDataException($productsLbl, 406);
        } 
    
        if(count($this->missingWarehouses) > 0){
            $warehousesLbl = '';
            foreach ($this->missingWarehouses as $key => $warehouse) {
                $warehousesLbl.= 'No existe la bodega '.$warehouse['code']. ' - '.$warehouse['name']." en el soft de palmera junior<br>";
            }
            throw new ImportDataException($warehousesLbl, 406);
        } 
    
        // Add a return statement here
        return null;
    }

    public function chunkSize(): int
    {
        return 20;
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


    private function inventory($row)
    {
        
        $stock=0;
        $warehouse=Warehouse::select(['id'])->where('code_foxcon',$row[0])->first();
        $product=Product::select(['id'])->where('code',$row[2])->first();
        // validando que existan el product y la bodega 
        if (!$product) {

            $product =  Product::create([
                "name" =>$row[3],
                "code" => $row[2]
             ]);
            //$this->missingProducts[] = ["code" => $row[2], "name" => $row[3]];
            //return;
        }
        Log::channel('foxcon-import')->info('-------------------- Producto: '. $product->name);
        if (!$warehouse) {
            $this->missingWarehouses[] = ["code" => $row[0], "name" => $row[1]];
            return;
        }
        Log::channel('foxcon-import')->info('-------------------- Bodega: '. $warehouse->name);
        //obteniendo el valor del stock del producto en el inventario
        $sumInventory=Inventory::where('warehouse_id', $warehouse->id)->where('product_id',$product->id)->first();
        
        if(isset($sumInventory)){
            $stock=$sumInventory->stock;
            Log::channel('foxcon-import')->info('-------------------- Existe inventario: '. true);
        }else{
            Log::channel('foxcon-import')->info('-------------------- Existe inventario: '. false);
        }
        //registrando o actualizando el inventario segun el valor en warehouse_id, product_id
        $inventory=Inventory::updateOrCreate([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id
        ],
        [
            'stock' => intval($stock)+intval($row[4]),
            'headquarter_id' => Auth::user()->headquarter_id
        ]);

        Log::channel('foxcon-import')->info('-------------------- Inventario padre actualizado');
        //consultando la cantidad de un producto en los detail_inventories
     
        $sum=$inventory->detailInventories()->where('plot',(isset($row[5]) && trim($row[5]) ? $row[5] : 'N/A'))->pluck('stock')->sum();
        Log::channel('foxcon-import')->info('-------------------- suma del lote existente: '. $sum);
        Log::channel('foxcon-import')->info('-------------------- suma del saldo + lote: '. (intval($row[4])+intval($sum)));
        //convirtir fechas desde el excel a laravel
        if(isset($row[7]) && trim($row[7]) != null && $row[7]!='N/A'){
            
            $dateExpiration = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7]);
        }else{
            $dateExpiration = null;
        }

        Log::channel('foxcon-import')->info('-------------------- fecha de expiración: '. ($dateExpiration != null ? $dateExpiration->format('Y-m-d') : 'NULL'));
        
        
            $inventory->detailInventories()->updateOrCreate(
                [
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'plot' => (isset($row[5]) && trim($row[5]) != null ? $row[5] : 'N/A')
                ],
                [
                    'code' => (isset($row[6]) && trim($row[6]) != null ? $row[6] : 'N/A'),
                    'date_expiration' => $dateExpiration,
                    'stock' => intval($row[4])+intval($sum),
                ]
            );

            Log::channel('foxcon-import')->info('-------------------- Inventario detail actualizado');
    }


public function onFailure(Failure ...$failures)
{
    // Handle the failures how you'd like.
    Log::channel('foxcon-import')->info('-------------------- Error en la importacion');
    foreach ($failures as $f) {
        Log::channel('foxcon-import')->info('-------------------- Error en la importacion: '. $f->row());
        Log::channel('foxcon-import')->info('-------------------- Error en la importacion: '. $f->attribute());
        Log::channel('foxcon-import')->info('-------------------- Error en la importacion: '. $f->errors());
        Log::channel('foxcon-import')->info('-------------------- Error en la importacion: '. $f->values());
    }
}
    
}
