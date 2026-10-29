<?php 

namespace App\Traits\Api\V2;

use App\ConfigurarionHeadquarterFoxcon;
use App\DocumentTypeFoxcon;
use App\FoxconDocument;
use App\FoxconDocumentDetail;
use App\Headquarter;
use App\Product;
use App\User;
use App\Warehouse;

Trait FoxconTrait {


    private function checkEnabledFoxconForHeadquarter($headquarterId){

        if(\App\Integration::find(1)->enabled &&
        in_array(
            \App\Headquarter::find(
                $headquarterId
            )->id,
            \App\Integration::find(1)->headquarters
        )
        ){
            return true;
        }
        return false;
    }



    private function takeANumberDocument(ConfigurarionHeadquarterFoxcon &$configurationHeadquarterFoxcon){

        $configurationHeadquarterFoxcon->consecutive = $configurationHeadquarterFoxcon->consecutive + 1;
        $configurationHeadquarterFoxcon->save();

        $lenght=strlen($configurationHeadquarterFoxcon->consecutive);
        $rest=6-$lenght;
        $val='';
        for ($i=0; $i < $rest; $i++) { 
            $val.='0';
        }
        return $val.$configurationHeadquarterFoxcon->consecutive;

    }

    private function getNomenclatureByWharehouse($wharehouseId, $documentTypeId) {

        
            
            $warehouse=Warehouse::select(['id','parent','warehousable_type'])->where('code_foxcon',$wharehouseId)->first();
            $id = $warehouse->id;
    
            if(($warehouse->warehousable_type === "App\SharedMechasnims" || $warehouse->warehousable_type  === "App\User" || $warehouse->warehousable_type  === "App\Cellar" || $warehouse->warehousable_type  === "App\Office")){
                $id=$warehouse->parent;
            }
    
        return  ConfigurarionHeadquarterFoxcon::where('warehouse_id', $id)->where('document_type_id', $documentTypeId)->first();
    }

    private function getConsuptionDocumentType(): DocumentTypeFoxcon{
        return DocumentTypeFoxcon::where('name', 'like', '%CONSUMOS%')->first();
    }

    private function getTransferDocumentType(): DocumentTypeFoxcon{
        return DocumentTypeFoxcon::where('name', 'like', '%TRASLADOS%')->first();
    }

    private function createDocument(
            DocumentTypeFoxcon $documentTypeFoxcon, 
            ConfigurarionHeadquarterFoxcon $configurationHeadquarterFoxcon, 
            $foxconNumberDocument, 
            $date, 
            string $nit,
            int $userIdFoxcon,
            Product $product,
            int $quantity = 0,
             $inWarehouse = null,
             $outWarehouse = null,
            string $plot = 'N/A',
            $observation = null
    ){
            
        $foxconHeaderDocument = $this->createHeaderDocument(
            $configurationHeadquarterFoxcon, 
            $foxconNumberDocument, 
            $date, 
            $nit, 
            $userIdFoxcon,
            $observation
        );

        $this->createBodyDocument(
            $foxconHeaderDocument,
            $documentTypeFoxcon, 
            $configurationHeadquarterFoxcon, 
            $foxconNumberDocument, 
            $date, 
            $nit, 
            $product,
            $quantity,
            $inWarehouse,
            $outWarehouse,
            $plot
        );

        return [$foxconHeaderDocument->id, $configurationHeadquarterFoxcon->nomenclature, $foxconNumberDocument];

    }

    private function createDocumentFromArray(
        DocumentTypeFoxcon $documentTypeFoxcon, 
        ConfigurarionHeadquarterFoxcon $configurationHeadquarterFoxcon, 
        $foxconNumberDocument, 
        $date, 
        string $nit,
        int $userIdFoxcon,
        $inWarehouse = null,
        $outWarehouse = null,
        $array = [],
        $observation = null
){
        
    $foxconHeaderDocument = $this->createHeaderDocument(
        $configurationHeadquarterFoxcon, 
        $foxconNumberDocument, 
        $date, 
        $nit, 
        $userIdFoxcon,
        $observation
    );

    if(count($array) > 0){
        foreach ($array as $item) {
            $this->createBodyDocument(
                $foxconHeaderDocument,
                $documentTypeFoxcon, 
                $configurationHeadquarterFoxcon, 
                $foxconNumberDocument, 
                $date, 
                $nit, 
                $item['product'],
                $item['quantity'],
                $inWarehouse,
                $outWarehouse,
                isset($item['plot']) ? $item['plot'] : 'N/A'
            );
        }
    }

    return [$foxconHeaderDocument->id, $configurationHeadquarterFoxcon->nomenclature, $foxconNumberDocument];

}

    private function createHeaderDocument(
            ConfigurarionHeadquarterFoxcon $configurationHeadquarterFoxcon, 
            $foxconNumberDocument, 
            $date, 
            string $nit,
            int $userIdFoxcon,
            $observation = null
        ) : FoxconDocument {

            return FoxconDocument::updateOrCreate(
                [
                    "numero"    => $foxconNumberDocument,
                    "documento" => $configurationHeadquarterFoxcon->nomenclature
                ],
                [
                    "timestamp" => $date,
                    "nit"       => $nit,
                    "sync"      => 0,
                    "anulado"   => 0,
                    "grabacion" => $date,
                    "usuario"   => $userIdFoxcon,
                    "observation" => $observation

                ]
            );

    }

    private function createBodyDocument(
        FoxconDocument $foxconDocument,
        DocumentTypeFoxcon $documentTypeFoxcon, 
        ConfigurarionHeadquarterFoxcon $configurationHeadquarterFoxcon, 
        $foxconNumberDocument, 
        $date, 
        string $nit,
        Product $product,
        int $quantity = 0,
        $inWarehouse,
        $outWarehouse,
        string $plot = 'N/A'
    ){

       switch ($documentTypeFoxcon->slug) {
        case 'E':
                $this->createEFoxconMoviment(
                    $foxconDocument,
                    $configurationHeadquarterFoxcon, 
                    $foxconNumberDocument, 
                    $date, 
                    $nit,
                    $product,
                    $quantity,
                    $plot,
                    $inWarehouse
                );
            break;
        case 'S':
                $this->createSFoxconMoviment(
                    $foxconDocument,
                    $configurationHeadquarterFoxcon, 
                    $foxconNumberDocument, 
                    $date, 
                    $nit,
                    $product,
                    $quantity,
                    $plot,
                    $outWarehouse
                );
            break;
        case 'ES':
                $this->createEFoxconMoviment(
                    $foxconDocument,
                    $configurationHeadquarterFoxcon, 
                    $foxconNumberDocument, 
                    $date, 
                    $nit,
                    $product,
                    $quantity,
                    $plot,
                    $inWarehouse
                );

                $this->createSFoxconMoviment(
                    $foxconDocument,
                    $configurationHeadquarterFoxcon, 
                    $foxconNumberDocument, 
                    $date, 
                    $nit,
                    $product,
                    $quantity,
                    $plot,
                    $outWarehouse
                );
            break;
        
        default:
            break;
       }
    }


    private function createEFoxconMoviment(
        FoxconDocument $foxconDocument,
        ConfigurarionHeadquarterFoxcon $configurationHeadquarterFoxcon, 
        int $foxconNumberDocument, 
        $date, 
        string $nit,
        Product $product,
        $quantity = 0,
        string $plot = 'N/A',
        $warehouse

    ) {
        return FoxconDocumentDetail::updateOrCreate(
            [
                "documento" => $configurationHeadquarterFoxcon->nomenclature,
                "numero"    => $foxconNumberDocument,
                "producto"  => $product->code,
                "bodega"    => $warehouse
            ],
            [
                "foxcon_document_id"    => $foxconDocument->id,
                "timestamp"             => $date,
                "nit"                   => $nit,
                "cantidad"              => $quantity,
                "tipoes"                => 'E',
                "created_at"            => $date,
                "plot"                  => $plot,
            ]
        );
    }


    private function createSFoxconMoviment(
        FoxconDocument $foxconDocument,
        ConfigurarionHeadquarterFoxcon $configurationHeadquarterFoxcon, 
        int $foxconNumberDocument, 
        $date, 
        string $nit,
        Product $product,
        $quantity = 0,
        string $plot = 'N/A',
        $warehouse

    ) {

        return FoxconDocumentDetail::updateOrCreate(
            [
                "documento" => $configurationHeadquarterFoxcon->nomenclature,
                "numero"    => $foxconNumberDocument,
                "producto"  => $product->code,
                "bodega"    => $warehouse
            ],
            [
                "foxcon_document_id"    => $foxconDocument->id,
                "timestamp"             => $date,
                "nit"                   => $nit,
                "cantidad"              => $quantity,
                "tipoes"                => 'S',
                "created_at"            => $date,
                "plot"                  => $plot,
            ]
        );
    }


    private function getNitHeadquarter($warehouseId){
        $warehouse=Warehouse::where('code_foxcon',$warehouseId)->first();

        if(($warehouse->warehousable_type === "App\SharedMechasnims" || $warehouse->warehousable_type  === "App\User" || $warehouse->warehousable_type  === "App\Cellar" || $warehouse->warehousable_type  === "App\Office") && $warehouseId != 1){
            return $warehouse->parentt->warehousable->nit;
        }

        return $warehouse->warehousable->nit;

        
    }

    private function getWarehouseByUser($userId){
        $code_foxcon = User::find($userId)->warehouse->code_foxcon;
        if(!$code_foxcon) abort(406,"Por favor, configure el codigo foxcon para bodega " .  User::find($userId)->warehouse->name );
        return  $code_foxcon;
    }

    private function getWarehouseById($warehouseId){
        $warehouse= Warehouse::withTrashed()->find($warehouseId);
        if(!$warehouse->code_foxcon) abort(406,"Por favor, configure el codigo foxcon para bodega " .  $warehouse->name );
        return  $warehouse->code_foxcon;
    }

    private function getUserIdFoxcon($id){

        $user=User::find($id);
        $id_foxcon=$user->headquarter->foxcon_user_id;
        $name=$user->headquarter->name;

        if(!$id_foxcon) abort(406,"Por favor, configure el id usuario foxcon de compras para la sede > ".$name);

        return $id_foxcon;

    }
}