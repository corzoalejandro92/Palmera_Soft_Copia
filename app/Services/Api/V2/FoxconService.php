<?php 

namespace App\Services\Api\V2;


use App\Product;
use App\Traits\Api\V2\FoxconTrait;
use App\User;

class FoxconService
{
    use FoxconTrait;


    public function createConsumptionDocument(
        int $userId,
        $date,
        int $productId,
        int $quantity = 0,
        string $plot = 'N/A',
        $observation = null
    ){

        if(!$this->checkEnabledFoxconForHeadquarter(User::find($userId)->headquarter_id)) return [null, null, null];

        $outWarehouse                   = $this->getWarehouseByUser($userId);
        $documentTypeFoxcon             = $this->getConsuptionDocumentType();
        $configurationHeadquarterFoxcon = $this->getNomenclatureByWharehouse($outWarehouse, $documentTypeFoxcon->id);
        $foxconNumberDocument           = $this->takeANumberDocument($configurationHeadquarterFoxcon);
        $nit                            = $this->getNitHeadquarter($outWarehouse);
        $product                        = Product::find($productId);
        $userIdFoxcon                   = $this->getUserIdFoxcon($userId);

        list($idDocument ,$typeDocument, $numberDocument) =  $this->createDocument(
            $documentTypeFoxcon, 
            $configurationHeadquarterFoxcon, 
            $foxconNumberDocument, 
            $date, 
            $nit,
            $userIdFoxcon,
            $product,
            $quantity,
            null,
            $outWarehouse,
            $plot,
            $observation
        );

        return [$idDocument,$typeDocument, $numberDocument];

    }

    public function createConsumptionDocumentFromArray(
        int $userId,
        $date,
        $array = [],
        $observation = null
    ){

        if(!$this->checkEnabledFoxconForHeadquarter(User::find($userId)->headquarter_id)) return [null, null, null];

        $outWarehouse                   = $this->getWarehouseByUser($userId);
        $documentTypeFoxcon             = $this->getConsuptionDocumentType();
        $configurationHeadquarterFoxcon = $this->getNomenclatureByWharehouse($outWarehouse, $documentTypeFoxcon->id);
        $foxconNumberDocument           = $this->takeANumberDocument($configurationHeadquarterFoxcon);
        $nit                            = $this->getNitHeadquarter($outWarehouse);
        $userIdFoxcon                   = $this->getUserIdFoxcon($userId);

        list($idDocument ,$typeDocument, $numberDocument) =  $this->createDocumentFromArray(
            $documentTypeFoxcon, 
            $configurationHeadquarterFoxcon, 
            $foxconNumberDocument, 
            $date, 
            $nit,
            $userIdFoxcon,
            null,
            $outWarehouse,
            $array,
            $observation
        );

        return [$idDocument,$typeDocument, $numberDocument];

    }

    public function createTransferDocument(
        int $userId,
        int $warehouseOutId,
        int $warehouseInId,
        $date,
        int $productId,
        int $quantity = 0,
        string $plot = 'N/A',
        string $foxconNumberDocument = null
    ){



        if(!$this->checkEnabledFoxconForHeadquarter(User::find($userId)->headquarter_id)) return [null, null, null];

        $inWarehouse                    = $this->getWarehouseById($warehouseInId);
        $outWarehouse                   = $this->getWarehouseById($warehouseOutId);
        $documentTypeFoxcon             = $this->getTransferDocumentType();
        $configurationHeadquarterFoxcon = $this->getNomenclatureByWharehouse($outWarehouse, $documentTypeFoxcon->id);

        if($foxconNumberDocument == null){
            $foxconNumberDocument       = $this->takeANumberDocument($configurationHeadquarterFoxcon);
        }
        
        $nit                            = $this->getNitHeadquarter($outWarehouse);
        $product                        = Product::find($productId);
        $userIdFoxcon                   = $this->getUserIdFoxcon($userId);

        list($idDocument ,$typeDocument, $numberDocument) =  $this->createDocument(
            $documentTypeFoxcon, 
            $configurationHeadquarterFoxcon, 
            $foxconNumberDocument, 
            $date, 
            $nit,
            $userIdFoxcon,
            $product,
            $quantity,
            $inWarehouse,
            $outWarehouse,
            $plot
        );

        return [$idDocument, $typeDocument, $numberDocument];

    }
}