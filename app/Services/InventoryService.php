<?php

namespace App\Services;

use App\FileExtend;
use App\Inventory;
use App\InventoryDetail;
use App\InventoryExpense;
use App\Scheduling;
use App\SharedMechasnims;
use App\TransferInventory;
use App\User;
use App\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryService {

    public function saveReportedMechismExpense(Request $request, Scheduling $scheduling, User $user){


        if(\App\Integration::find(1)->enabled && 
            in_array(
                \App\Headquarter::find(
                    $user->headquarter_id
                )->id , \App\Integration::find(1)->headquarters
        )){
                $inventories = Inventory::
                whereIn('product_id', $request->input('reported_products'))
                ->where('warehouse_id', Warehouse::whereHasMorph('warehousable', [SharedMechasnims::class], function ($q) use ($user) {$q->where('headquarter_id', $user->headquarter_id);})->first()->id)
                ->where('stock', '>', 0)
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique(function ($item) {
                    return $item['plot'].$item['product_id'];
                });


                if($inventories->count() > 0) {
                    foreach ($inventories as $key => $inventory) {

                        $foxconDocument= null;

                        //Validar si la integración con foxcon está activa
                        if(\App\Integration::find(1)->enabled &&
                        in_array(
                            \App\Headquarter::find(
                                $user->headquarter_id
                            )->id,
                            \App\Integration::find(1)->headquarters
                        )) {

                            $request->merge(['observation_foxcon' => $scheduling->name ?? null, 'created_at' => $request->timestamp]);

                            $foxconService =  new FoxconService();
                            $foxconDocument =   $foxconService->saveMechanismExpense($inventory->detailInventory->id, $inventory->product_id, $inventory->warehouse_id, $user->id, $request);
                        }


                        //se utiliza firstOrCreate para evitar duplicados
                        $inventoryExpense = InventoryExpense::updateOrCreate(
                            [
                                "inventory_detail_id" => $inventory->detailInventory->id
                            ],
                            [
                                "warehouse_id"          => $inventory->warehouse_id,
                                "product_id"            => $inventory->detailInventory->product_id,
                                "control_id"            => null,
                                "crm_schedule_id"       => $scheduling->crm_schedule_id,
                                "foxcon_document_id"    => $foxconDocument,
                                "headquarter_id"        => $user->headquarter_id,
                                "user_id"               => $user->id,
                                "code"                  => 'N/A',
                                "plot"                  => 'N/A',
                                "date_expiration"       => null,
                                "quantity"              => 1,
                                "health_register"       => 'N/A',
                                "product_report"        => null,
                                "dose_quantity"         => null,
                                "created_at"            => $request->input("created_at"),
                            ]
                        );


                        $inventory_detail_id = InventoryDetail::
                        where('product_id', $inventory->product_id)
                        ->where('inventory_id', $inventory->id)
                        ->where('stock', '>', 0)
                        ->orderBy('created_at', 'desc')
                        ->first();

                        if($inventory_detail_id) {

                            $stock = $inventory_detail_id->stock - 1;
                            $inventory_detail_id->stock = $stock;
                            $inventory_detail_id->save();

                            //actualizar inventario padre tambien
                           

                            if($inventory) {
                                $stock2 = $inventory->stock - 1;
                                $inventory->stock = $stock2;
                                $inventory->save();
                            } else {
                                DB::rollback();
                                abort(500, 'No hay stock para consumir del(los) producto(s) reportado(s) en la bodega de mecanismos prestados de la sede '. $user->headquarter->name);
                            }

                        }


                    }
                } else {
                    abort(500, 'No hay stock para consumir del(los) producto(s) reportado(s) en la bodega de mecanismos prestados de la sede '. $user->headquarter->name);
                }
            }
    }


    public function saveReplaceMechanismTransfer(Request $request, Scheduling $scheduling, User $user){

        $exitInventories = Warehouse::whereHasMorph('warehousable',[User::class], function($q) use($user){$q->where('id',$user->id);})->first();
        $inWarehouse = Warehouse::whereHasMorph('warehousable',[SharedMechasnims::class], function($q) use($user){$q->where('headquarter_id',$user->headquarter_id);})->first();

        $foxconDocument= null;
        
        //Validar si la integración con foxcon está activa
        if(\App\Integration::find(1)->enabled && 
        in_array(
            \App\Headquarter::find(
                $user->headquarter_id
            )->id , \App\Integration::find(1)->headquarters
        )){
            $foxconService =  new FoxconService;
            $request->merge(['observation_foxcon' => $scheduling->name ?? null, 'created_at' => $request->timestamp]);
            $foxconDocument =   $foxconService->saveReplaceMechanisms($request->input('reported_products'), $exitInventories->id, $inWarehouse->id, $user->id, $request);
        }
        

        //guaradar el registro de transferencia en las tablas locales
        $inventoryTransfer = TransferInventory::create(
            [
                "date_transfer"         => Carbon::now()->format('Y-m-d H:i:s'),
                "headquarter_id"        => $user->headquarter_id,
                "warehouse_id"          => $exitInventories->id,
                "warehouse_transfer"    => $inWarehouse->id,
                "document_type_id"      => 2,
                "document_foxcon_id"    => $foxconDocument,
                "observation"           => $request->observation,
                "user_id"               => $user->id,
            ]
        );

        foreach ($request->input('reported_products') as $productId) {

            //ingresar cada transfery details a las tablas locales
            $inventoryTransfer->detailTranferInventory()->create([
                "foxcon"                => 'N/A',
                "plot"                  => 'N/A',
                "date_expiration"       => null,
                "quantity"              => 1,
                "transfer_quantity"     => 1,
                "product_id"            => $productId
            ]);

            //actualizar el inventario del producto dentro del inventario de salida
            $inventoryDetailExit = InventoryDetail::
                where('product_id',$productId)
                ->where('warehouse_id', $exitInventories->id)
                ->orderBy('created_at','desc')
                ->where('stock','>',0)
                ->first();

                if($inventoryDetailExit){

                    $stock = $inventoryDetailExit->stock - 1;
                    $inventoryDetailExit->stock = $stock;
                    $inventoryDetailExit->save();
        
                    //actualizar inventario padre tambien
                    $inventoryExit = Inventory::
                    where('product_id',$productId)
                    ->where('warehouse_id', $exitInventories->id)
                    ->orderBy('created_at','desc')
                    ->where('stock','>',0)
                    ->first();

                    if($inventoryExit){
                        $stock2 = $inventoryExit->stock - 1;
                        $inventoryExit->stock = $stock2;
                        $inventoryExit->save();

                    }

                }else{
                    DB::rollback();
                    abort(500, 'El producto a transferir en el inventario de la bodega de salida no se encuentra disponible');
                }


            //actualizar el inventario del producto dentro del inventario de entrada

            $inventoryIn = Inventory::firstOrNew(
                [
                    "warehouse_id" => $inWarehouse->id,
                    "product_id"   => $productId,
                ],
                [
                    "headquarter_id"        => $user->headquarter_id
                ]
            );

            if($inventoryIn->stock ==  null){
                $inventoryIn->stock = 1;
            }else{
                $inventoryIn->stock = $inventoryIn->stock + 1;
            }

            $inventoryIn->save();

            //actualizar el detalle del inventario de entrada
            $inventoryDetailIn = $inventoryIn->detailInventory()->firstOrNew(
                [
                    "warehouse_id" => $inWarehouse->id,
                    "product_id"   => $productId,
                    "plot"         => 'N/A'
                ],
                [
                    
                ]
            );
           

            if($inventoryDetailIn->stock ==  null){
                $inventoryDetailIn->stock = 1;
            }else{
                $inventoryDetailIn->stock = $inventoryDetailIn->stock + 1;
            }

            $inventoryDetailIn->save();
        }
            
    }


    public function installTransferMechanism($inverse = false, $inWarehouse, $outWarehouse, $mechanismId, $quantity, $user = null){
       
        $user = $user != null ? $user : Auth::user();
        $foxconDocument= null;
        
        //Validar si la integración con foxcon está activa
        if(\App\Integration::find(1)->enabled && 
        in_array(
            \App\Headquarter::find(
                $user->headquarter_id
            )->id , \App\Integration::find(1)->headquarters
        )){
            $foxconService =  new FoxconService;
            $foxconDocument =   $foxconService->transferMechanisms($inverse, $inWarehouse, $outWarehouse, $mechanismId, $quantity, $user->id);
        }

        //guaradar el registro de transferencia en las tablas locales
        $inventoryTransfer = TransferInventory::create(
            [
                "date_transfer"         => Carbon::now()->format('Y-m-d H:i:s'),
                "headquarter_id"        => $user ? $user->headquarter_id :  Auth::user()->headquarter_id,
                "warehouse_id"          => $outWarehouse,
                "warehouse_transfer"    => $inWarehouse,
                "document_type_id"      => 2,
                "document_foxcon_id"    => $foxconDocument,
                "observation"           => 'Transferencia automática por asociación(QR)/Retiro de Mecanismo',
                "user_id"               => $user ? $user->id : Auth::user()->id,
            ]
        );

        $mechanism = FileExtend::find($mechanismId);

        foreach ($mechanism->extendible->products as $product) {

            //ingresar cada transfer<details a las tablas locales
            $inventoryTransfer->detailTranferInventory()->create([
                "foxcon"                => 'N/A',
                "plot"                  => 'N/A',
                "date_expiration"       => null,
                "quantity"              => 1,
                "transfer_quantity"     => 1,
                "product_id"            => $product->id,
                "facturable"            => false
            ]);

            //actualizar el inventario del producto dentro del inventario de salida
            $inventoryDetailExit = InventoryDetail::
                where('product_id',$product->id)
                ->where('warehouse_id', $outWarehouse)
                ->orderBy('created_at','desc')
                ->where('stock','>',0)
                ->first();

                if($inventoryDetailExit){

                    $stock = $inventoryDetailExit->stock - 1;
                    $inventoryDetailExit->stock = $stock;
                    $inventoryDetailExit->save();
        
                    //actualizar inventario padre tambien
                    $inventoryExit = Inventory::
                    where('product_id',$product->id)
                    ->where('warehouse_id', $outWarehouse)
                    ->orderBy('created_at','desc')
                    ->where('stock','>',0)
                    ->first();

                    if($inventoryExit){
                        $stock2 = $inventoryExit->stock - 1;
                        $inventoryExit->stock = $stock2;
                        $inventoryExit->save();

                    }

                }else{
                    DB::rollback();
                    abort(500, 'El producto a transferir en el inventario de la bodega de salida no se encuentra disponible');
                }


            //actualizar el inventario del producto dentro del inventario de entrada

            $inventoryIn = Inventory::firstOrNew(
                [
                    "warehouse_id" => $inWarehouse,
                    "product_id"   => $product->id,
                ],
                [
                    "headquarter_id"        => $user ? $user->headquarter_id :  Auth::user()->headquarter_id,
                ]
            );

            if($inventoryIn->stock ==  null){
                $inventoryIn->stock = 1;
            }else{
                $inventoryIn->stock = $inventoryIn->stock + 1;
            }

            $inventoryIn->save();

            //actualizar el detalle del inventario de entrada
            $inventoryDetailIn = $inventoryIn->detailInventory()->firstOrNew(
                [
                    "warehouse_id" => $inWarehouse,
                    "product_id"   => $product->id,
                    "plot"         => 'N/A'
                ],
                [
                    
                ]
            );
           

            if($inventoryDetailIn->stock ==  null){
                $inventoryDetailIn->stock = 1;
            }else{
                $inventoryDetailIn->stock = $inventoryDetailIn->stock + 1;
            }

            $inventoryDetailIn->save();
        }

    }

    public function removeTransferMechanism($inverse = false, $inWarehouse, $outWarehouse, $mechanismId, $quantity, $user = null, Request $request = null){
       
        $user = $user != null ? $user : Auth::user();
        $foxconDocument= null;
        
        //Validar si la integración con foxcon está activa
        if(\App\Integration::find(1)->enabled && 
        in_array(
            \App\Headquarter::find(
                $user->headquarter_id
            )->id , \App\Integration::find(1)->headquarters
        )){
            $scheduling = Scheduling::find($request->scheduling_id);
            $request->merge(['observation_foxcon' => $scheduling->name ?? null, '']);
            $foxconService =  new FoxconService;
            $foxconDocument =   $foxconService->transferMechanisms($inverse, $inWarehouse, $outWarehouse, $mechanismId, $quantity, $user->id, $request);
        }

        //guaradar el registro de transferencia en las tablas locales
        $inventoryTransfer = TransferInventory::create(
            [
                "date_transfer"         => Carbon::now()->format('Y-m-d H:i:s'),
                "headquarter_id"        => $user ? $user->headquarter_id :  Auth::user()->headquarter_id,
                "warehouse_id"          => $outWarehouse,
                "warehouse_transfer"    => $inWarehouse,
                "document_type_id"      => 2,
                "document_foxcon_id"    => $foxconDocument,
                "observation"           => 'Transferencia automática por Retiro de Mecanismo',
                "user_id"               => $user ? $user->id : Auth::user()->id,
                "enabled"               => 0
            ]
        );

        $mechanism = FileExtend::find($mechanismId);

        foreach ($mechanism->extendible->products as $product) {

            //ingresar cada transfer<details a las tablas locales
            $inventoryTransfer->detailTranferInventory()->create([
                "foxcon"                => 'N/A',
                "plot"                  => 'N/A',
                "date_expiration"       => null,
                "quantity"              => 1,
                "transfer_quantity"     => 1,
                "product_id"            => $product->id,
                "facturable"            => false
            ]);

            //actualizar el inventario del producto dentro del inventario de salida
            $inventoryDetailExit = InventoryDetail::
                where('product_id',$product->id)
                ->where('warehouse_id', $outWarehouse)
                ->orderBy('created_at','desc')
                ->where('stock','>',0)
                ->first();

                if($inventoryDetailExit){

                    $stock = $inventoryDetailExit->stock - 1;
                    $inventoryDetailExit->stock = $stock;
                    $inventoryDetailExit->save();
        
                    //actualizar inventario padre tambien
                    $inventoryExit = Inventory::
                    where('product_id',$product->id)
                    ->where('warehouse_id', $outWarehouse)
                    ->orderBy('created_at','desc')
                    ->where('stock','>',0)
                    ->first();

                    if($inventoryExit){
                        $stock2 = $inventoryExit->stock - 1;
                        $inventoryExit->stock = $stock2;
                        $inventoryExit->save();

                    }

                }else{
                    DB::rollback();
                    abort(500, 'El producto a transferir en el inventario de la bodega de salida no se encuentra disponible');
                }


            //actualizar el inventario del producto dentro del inventario de entrada

            $inventoryIn = Inventory::firstOrNew(
                [
                    "warehouse_id" => $inWarehouse,
                    "product_id"   => $product->id,
                ],
                [
                    "headquarter_id"        => $user ? $user->headquarter_id :  Auth::user()->headquarter_id,
                ]
            );

            if($inventoryIn->stock ==  null){
                $inventoryIn->stock = 1;
            }else{
                $inventoryIn->stock = $inventoryIn->stock + 1;
            }

            $inventoryIn->save();

            //actualizar el detalle del inventario de entrada
            $inventoryDetailIn = $inventoryIn->detailInventory()->firstOrNew(
                [
                    "warehouse_id" => $inWarehouse,
                    "product_id"   => $product->id,
                    "plot"         => 'N/A'
                ],
                [
                    
                ]
            );
           

            if($inventoryDetailIn->stock ==  null){
                $inventoryDetailIn->stock = 1;
            }else{
                $inventoryDetailIn->stock = $inventoryDetailIn->stock + 1;
            }

            $inventoryDetailIn->save();
        }

    }

    public function saveReportedExpensePlot($warehouse, $products, User $user, $facturable=false){
        $foxconDocument= null;
        
        //Validar si la integración con foxcon está activa
        if((
                \App\Integration::find(1)->enabled && 
                in_array(
                    \App\Headquarter::find(
                        session('headquarter_id')
                    )->id , \App\Integration::find(1)->headquarters
                ) && $facturable == 0
            ) 
                
                ||

                (
                    $warehouse == 1 &&
                    in_array( 99999999 , \App\Integration::find(1)->headquarters) &&
                    $facturable == 0
                )
        ){
            $foxconService =  new FoxconService;
            $foxconDocument =   $foxconService->saveExpense( $products, $warehouse, $user->id);
        }
        
        foreach ($products as $key => $product) {

            $inventory_detail = InventoryDetail::where('warehouse_id', $warehouse)
                ->where('product_id', $product['product_id'])
                ->where('plot', $product['plot'])
                ->where('stock','>=', intval($product['transfer_quantity']))
                ->orderBy('created_at','desc')
                ->first();

            //se utiliza firstOrCreate para evitar duplicados
            $inventoryExpense = InventoryExpense::create(
                [
                    "inventory_detail_id"   => $inventory_detail->id,
                    "warehouse_id"          => $warehouse,
                    "product_id"            => $product['product_id'],
                    "control_id"            => null,
                    "crm_schedule_id"       => null,
                    "foxcon_document_id"    => $foxconDocument,
                    "headquarter_id"        => $user->headquarter_id,
                    "user_id"               => $user->id,
                    "code"                  => $product['foxcon'],
                    "plot"                  => $product['plot'],
                    "date_expiration"       => $product['date_expiration'],
                    "quantity"              => $product['transfer_quantity'],
                    "health_register"       => 'N/A',
                    "product_report"        => null,
                    "dose_quantity"         => null
                ]
            );

        
            if($inventory_detail){

                $stock = $inventory_detail->stock - intval($product['transfer_quantity']);
                $inventory_detail->stock = $stock;
                $inventory_detail->save();

                //actualizar inventario padre tambien
                $inventorym = Inventory::find($inventory_detail->inventory_id);
                if($inventorym){
                    $stock2 = $inventorym->stock - intval($product['transfer_quantity']);
                    $inventorym->stock = $stock2;
                    $inventorym->save();
                }else{
                    DB::rollback();
                    abort(500,'El inventario del producto a intervenir ya no se encuentra disponible en la bodega de mecanismos prestados');
                }
                
            }else{
                DB::rollback();
                abort(500,'El inventario del producto a intervenir ya no se encuentra disponible en la bodega de mecanismos prestados');
            }
        }
    }
}
