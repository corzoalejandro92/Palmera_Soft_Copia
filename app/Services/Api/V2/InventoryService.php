<?php 

namespace App\Services\Api\V2;

use App\Consumption;
use App\Http\Resources\InventoryPendingResource;
use App\Http\Resources\InventoryResource;
use App\Inventory;
use App\InventoryDetail;
use App\Product;
use App\TransferInventory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryService
{

    protected $inventoryDetail;
    protected $inventory;
    protected $transferInventory;
    protected $transferInventoryDetail;

    protected $consumption;

    public function getByUser(Request $request){
        $inventory = Inventory::with([
            "product", "detailInventories"
        ])->whereHas('warehouse', function($query){
            $query->whereHasMorph('warehousable', [User::class], function($query){
                $query->where('id', Auth::id());
            });
        })->get();

        return InventoryResource::collection( $inventory);
    }

    public function getPendingToConfirm(Request $request){

        

        $inventory = TransferInventory::with([
             "detailTranferInventory.product"
        ])->whereHas('warehouseTransfer', function($query){
            $query->whereHasMorph('warehousable', [User::class], function($query){
                $query->where('id', Auth::id());
            });
        })->where('enabled', 1)->get();

        return InventoryPendingResource::collection( $inventory);
    }

    public function acceptTransfer(int $transferId){
        $this->transferInventory = TransferInventory::with('detailTranferInventory')->find($transferId);
        $this->saveTransferInInventory();
    }

    public function deductUserInventoryByConsumption(Consumption $consumption){

        $this->consumption = $consumption;
        $this->findInventoryDetailByConsumption();
        $this->findInventoryByCosumption();

        $this->inventoryDetail->stock -= $this->consumption->quantity;
        $this->inventoryDetail->save();

        $this->inventory->stock -= $this->consumption->quantity;
        $this->inventory->save();

        $observation = optional(optional($this->consumption->schedule->schedulings)->last())->name;
        
        (new FoxconService())->createConsumptionDocument(
            Auth::id(),
            $this->consumption->created_at,
            $this->inventoryDetail->product_id,
            $this->consumption->quantity,
            $this->inventoryDetail->plot,
            $observation
        );

        return true;

    }

    public function deductUserInventoryByConsumptionFromArray(array $consumptions){

        $array = [];

        foreach ($consumptions as $idx => $consumption) {
            $this->consumption = $consumption;
            $this->findInventoryDetailByConsumption();
            $this->findInventoryByCosumption();
    
            $this->inventoryDetail->stock -= $this->consumption->quantity;
            $this->inventoryDetail->save();
    
            $this->inventory->stock -= $this->consumption->quantity;
            $this->inventory->save();

            $array[] = [
                'product' => Product::find($this->inventoryDetail->product_id),
                'quantity' => $this->consumption->quantity,
                'plot' => $this->inventoryDetail->plot,
            ];
        }
       

        $observation = optional(optional($this->consumption->schedule->schedulings)->last())->name;
        
        
        (new FoxconService())->createConsumptionDocumentFromArray(
            Auth::id(),
            $this->consumption->created_at,
            $array,
            $observation
        );

        return true;

    }

    private function saveTransferInInventory(){
        try {
            DB::beginTransaction();

                $this->transferInventory->detailTranferInventory->each(function($detail){
                    $this->transferInventoryDetail = $detail;
                    $this->findInventory();
                    $this->updateOrCreateInventory();
                    $this->findInventoryDetail(true);
                    $this->updateOrCreateInventoryDetail();
                    $this->disableTransfer();
                });

                list($idDocument, $typeDocument, $numberDocument) = (new FoxconService())->createTransferDocument(
                    Auth::id(),
                    $this->transferInventory->warehouse_id,
                    $this->transferInventory->warehouse_transfer,
                    Carbon::now(),
                    $this->transferInventoryDetail->product_id,
                    $this->transferInventoryDetail->transfer_quantity,
                    $this->transferInventoryDetail->plot,
                    $this->transferInventory->pre_document_number
                );

                $this->transferInventory->document_foxcon_id = $idDocument;
                $this->transferInventory->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, $th->getMessage());
        }
    }

    private function findInventory(){

        $this->inventory = Inventory::where('product_id', $this->transferInventoryDetail->product_id)
            ->where('warehouse_id', $this->transferInventory->warehouse_transfer)
            ->first();
    }

    private function findInventoryByCosumption(){

        $this->inventory = Inventory::where('product_id', $this->inventoryDetail->product_id)
            ->where('warehouse_id', $this->inventoryDetail->warehouse_id)
            ->first();

        if(!$this->inventory) abort(400, 'No se encontró el inventario del producto ' . Product::find($this->inventoryDetail->product_id)->name . ' en la bodega ' . $this->inventoryDetail->warehouse->name);
    }

    private function findInventoryDetail(bool $continueOnNull = false){

        $this->inventoryDetail = InventoryDetail::where('product_id', $this->transferInventoryDetail->rpoduct_id)
            ->where('warehouse_id', $this->transferInventory->warehouse_transfer)
            ->where('inventory_id', $this->inventory->id)
            ->where('plot', $this->transferInventoryDetail->plot)
            ->first();

        if(!$continueOnNull){
            if(!$this->inventoryDetail) abort(400, 'No se encontró el lote ' . $this->transferInventoryDetail->plot . ' del producto ' . Product::find($this->transferInventoryDetail->product_id)->name . ' en el inventario de la bodega ' . $this->transferInventory->warehouse_transfer);
        }
    }

    private function findInventoryDetailByConsumption(){

        $this->inventoryDetail = InventoryDetail::find($this->consumption->inventory_detail_id);
        if(!$this->inventoryDetail) abort(400, 'No se encontró el lote ' . $this->transferInventoryDetail->plot . ' del producto ' . Product::find($this->transferInventoryDetail->product_id)->name . ' en el inventario de la bodega ' . $this->transferInventory->warehouse_transfer);
    }

    private function updateOrCreateInventoryDetail(){

        if($this->inventoryDetail){
            $this->inventoryDetail->stock +=$this->transferInventoryDetail->transfer_quantity;
            $this->inventoryDetail->save();
        }else{

            InventoryDetail::create([
                'inventory_id' => $this->inventory->id,
                'warehouse_id' => $this->transferInventory->warehouse_transfer,
                'product_id' => $this->transferInventoryDetail->product_id,
                'code' => $this->transferInventoryDetail->foxcon,
                'plot' => $this->transferInventoryDetail->plot,
                'date_expiration' => $this->transferInventoryDetail->date_expiration,
                'stock' => $this->transferInventoryDetail->transfer_quantity,
                'facturable' => $this->transferInventoryDetail->facturable,
            ]);
        }
    }

    private function updateOrCreateInventory(){

        if($this->inventory){
            $this->inventory->stock += $this->transferInventoryDetail->transfer_quantity;
            $this->inventory->save();
        }else{
           $this->inventory = Inventory::create([
                'headquarter_id' => auth()->user()->headquarter_id,
                'warehouse_id' => $this->transferInventory->warehouse_transfer,
                'product_id' => $this->transferInventoryDetail->product_id,
                'stock' => $this->transferInventoryDetail->transfer_quantity,
                'enabled' => 1
            ]);
        }
    }
    private function disableTransfer(){
        $this->transferInventory->enabled = 0;
        $this->transferInventory->save();
    }

}