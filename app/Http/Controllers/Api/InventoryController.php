<?php

namespace App\Http\Controllers\Api;

use App\ConfirmationTransferInventory;
use App\FoxconDocument;
use App\Headquarter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Inventory;
use App\ConfigurarionHeadquarterFoxcon;
use App\DetailConfirmationTransfer;
use App\DocumentTypeFoxcon;
use App\InventoryExpense;
use App\InventoryDetail;
use App\Models\Api\Product;
use App\Product as AppProduct;
use App\Scheduling;
use App\TransferInventory;
use App\User;
use App\Warehouse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class InventoryController extends Controller
{
    public function index(Request $request)
    {  
		//obtener la bodega del técnico
		$user = User::with('warehouse')->find($this->getUserIDToken($request));
		$warehouse = $user->warehouse->id;
		

        //data del inventario de los técnicos por separado
        $users = User::with(['warehouse.inventory' =>function($inventory){
            $inventory->with(['detailInventories'=> function($q){
                $q->with('product')->where('facturable',false)->whereHas('product', function($product){
                    $product->whereNotIn('category_id',[4,5]);
                })->where('stock', '>', 0);
            }])->where('stock', '>', 0);
        }])->whereIn('id',[$user->id])->get()->unique('id')->keyBy('id');


        $users = $users->map(function($user){
            $user->warehouse->inventory =  $user->warehouse->inventory->map(function($inventory) {
                $inventory->stock =  $inventory->detailInventories->sum('stock');
                return $inventory;
            });
            
            return $user;
        });

        return $this->success($users->toArray()[$user->id]['warehouse']['inventory']);

    }

    public function toConfirm(Request $request)
    {  

        try {
            $user = User::with('warehouse')->find($this->getUserIDToken($request));
            $warehouse = $user->warehouse;
    
            $confirmationTransfers = TransferInventory::with(['warehouse','detailTranferInventory.product'])
            ->orderBy('id', 'desc')
            ->where('warehouse_transfer',$warehouse->id)
            ->where('enabled',1)
            ->get();
    
            return $this->success($confirmationTransfers->toArray()); 
        }catch(\Exception $ex){
            
                return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);
        }
       

    }

    public function saveConfirm(Request $request){
        try {
            DB::beginTransaction();

            $user = User::with('warehouse')->find($this->getUserIDToken($request));
            $warehouse = $user->warehouse;

          
            $confirmationTransfer = new ConfirmationTransferInventory;
            $inventory = new Inventory;

            $transferInventory  = TransferInventory::find($request->id_transfer);

            $confirmationTransfer->headquarter_id = $user->headquarter_id;
            $confirmationTransfer->date_transfer = $confirmationTransfer->date_transfer;
            $confirmationTransfer->warehouse_exit = $confirmationTransfer->warehouse_id;
            $confirmationTransfer->warehouse_arrival = $confirmationTransfer->warehouse_transfer; 
            $confirmationTransfer->date_confirmation = Carbon::now()->format('Y-m-d 00:00:00');
            $confirmationTransfer->observation =  $confirmationTransfer->observation; 
            $confirmationTransfer->save();
            
            $cont=0;
            
            if( $transferInventory->detailTranferInventory->count() > 0){
                foreach ($transferInventory->detailTranferInventory as $indx => $content) {
                       //guardar
                    $confirmationTransfer->detailConfirmationTranferInventory()->create([
                        'product_id'        => $content->product_id,
                        'foxcon'            => $content->foxcon,
                        'plot'              => $content->plot,
                        'quantity'          => $content->quantity,
                        'date_expiration'   => $content->date_expiration,
                        'approved'          => 1,
                        'quantity_received' => $content->transfer_quantity,
                    ]);
                    
                    if($cont === 0){
                        //Validar si la integración con foxcon está activa
                        if(\App\Integration::find(1)->enabled && 
                        in_array(
                            \App\Headquarter::find(
                                $user->headquarter_id
                            )->id , \App\Integration::find(1)->headquarters
                        )){
                            $this->saveFoxconDocumentsWarehouse($transferInventory);
                        }
                        $cont=1;
                    }
                    
                }
            }

            $transferInventory->enabled = 0;
            $transferInventory->save();

            $products = $transferInventory->detailTranferInventory()->whereHas('product', function ($product){$product->where('category_id','!=',4);})->get();

            if( $products->count() > 0){
                foreach ($products as $indx => $content) {

                    $productInventory=null;
                    $num_stock=0;
                        $productInventorys = Inventory::where('warehouse_id', $transferInventory->warehouse_transfer)
                        ->where('product_id',$content->product_id)
                        ->get();

                        if($content->file_extend_id){
                            
                            $productInventorysDeduct = Inventory::where('warehouse_id',$transferInventory->warehouse_id)
                            ->where('product_id',$content->product_id)
                            ->where('file_extend_id',$content->file_extend_id)
                            ->get();
                        }else{
                            $productInventorysDeduct = Inventory::where('warehouse_id',$transferInventory->warehouse_id)
                            ->where('product_id',$content->product_id)
                            ->get();
                        }

                        $product=Product::find($content->product_id);
                        if(count($productInventorysDeduct) === 0) abort(406,"Stock insuficiente del producto $product->name");
                        foreach($productInventorysDeduct as $productInventoryDeduct){
                            $decreseValue=0;
                            $decreseValue=$content->transfer_quantity;
                            $productInventoryDeduct->stock -= $decreseValue;
                            $productInventoryDeduct->save();
                            $detailsInventori=$productInventoryDeduct->detailInventories->where('plot',$content->plot);
                            
                            foreach ($detailsInventori as $inventorydet){
                                $stock_old=$inventorydet->stock-$decreseValue;
                                $inventorydet->stock=$stock_old;
                                $inventorydet->save();
                                if($inventorydet->stock < 0){
                                    abort('406', "La cantidad a transferir del producto '".$productInventoryDeduct->product->name."', NO debe ser mayor al Stock del lote '".$inventorydet->plot."'");
                                }
                            }
                           if($productInventorysDeduct->first()->stock < 1){ 
                                $productInventoryDeduct->delete();
                                $detailsInventori->each->delete();
                            }
                        }
                        foreach($productInventorys as $productInventory){
                            $productInventory->stock += $content->transfer_quantity;
                            $productInventory->save();
                        }
                        if(filled($productInventory)){
                            $num_stock=optional($productInventory->detailInventories)->where('warehouse_id',$transferInventory->warehouse_transfer)->where('product_id',$content->product_id)->where('plot',strtoupper($content->plot))->pluck('stock')->sum();
                        }
                        if(count($productInventorys) > 0){


                            $productInventory->detailInventory()->updateOrCreate(
                                [ 
                                    'warehouse_id'       => $transferInventory->warehouse_transfer,
                                    'product_id'         => $content->product_id,
                                    'plot'               => strtoupper($content->plot),
                                ],
                                [
                                    'code'               => strtoupper($content->foxcon),
                                    'date_expiration'    => $content->date_expiration,
                                    'stock'              => $num_stock+$content->transfer_quantity,
                                    'facturable'         => $content->facturable
                                ]);

                        
                        }else{

                            $inventory = Inventory::create([
                                'headquarter_id'        => $user->headquarter->id,
                                'warehouse_id'          => $transferInventory->warehouse_transfer,
                                'product_id'            => $content->product_id,
                                'stock'                 => $content->transfer_quantity
                            ]);

                            $inventory->detailInventory()->updateOrCreate(
                                [ 
                                    'warehouse_id'       => $transferInventory->warehouse_transfer,
                                    'product_id'         => $content->product_id,
                                    'plot'               => strtoupper($content->plot),
                                ],
                                [
                                    'code'               => strtoupper($content->foxcon),
                                    'date_expiration'    => $content->date_expiration,
                                    'stock'              => $num_stock+$content->transfer_quantity,
                                    'facturable'         => $content->facturable
                                ]);

    
                        }
                    
                }
            }

            DB::commit();
            return $this->success(true); 
    }catch(\Exception $ex){
        DB::rollBack();
    return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);
    
    }
    }

    private function saveFoxconDocumentsWarehouse(TransferInventory $transferInventory){

        //separar los productos que son EPPS (para consumo) del resto de los productos
        $epps = $transferInventory->detailTranferInventory()->whereHas('product', function ($product){$product->where('category_id',4);})->get();
        $products = $transferInventory->detailTranferInventory()->whereHas('product', function ($product){$product->where('category_id','!=',4);})->get();

        


        if($products->count() > 0){
            $configFoxcon=$this->getDocumentFoxconById($transferInventory->document_type_id);
            //$numero=$this->getNumberFoxcon($configFoxcon->consecutive+1);
            $foxconDocuments = FoxconDocument::create([
                "documento" => $configFoxcon->nomenclature,
                "numero" => $transferInventory->pre_document_number,
                "timestamp" => now(),
                "grabacion" => now(),
                "sync" => 0,
                "nit" => $this->getNitFoxconByWid($transferInventory->warehouse_id),
                "usuario" => $this->getUserIdFoxcon($transferInventory->user_id),
            ]);
            // $configFoxcon->consecutive = $numero;
            // $configFoxcon->save();
            foreach ($products as $indx => $content) {
                    $foxconDocuments->details()->create([
                            'documento'          => $configFoxcon->nomenclature,
                            'numero'             => $transferInventory->pre_document_number,
                            'timestamp'          => now(),
                            'producto'           => $this->getProductFoxcon($content->product_id),
                            'bodega'             => $this->getWarehouseFoxconByWid($transferInventory->warehouse_id),
                            'cantidad'           => $content->transfer_quantity,
                            'nit'                => $this->getNitFoxconByWid($transferInventory->warehouse_id),
                            'tipoes'             => "S",
                            'plot'               => strtoupper($content->plot)
                    ]);
                    $foxconDocuments->details()->create([
                            'documento'          => $configFoxcon->nomenclature,
                            'numero'             => $transferInventory->pre_document_number,
                            'timestamp'          => now(),
                            'producto'           => $this->getProductFoxcon($content->product_id),
                            'bodega'             => $this->getWarehouseFoxconByWid($transferInventory->warehouse_transfer),
                            'cantidad'           => $content->transfer_quantity,
                            'nit'                => $this->getNitFoxconByWid($transferInventory->warehouse_transfer),
                            'tipoes'             => "E",
                            'plot'               => strtoupper($content->plot)
                    ]);
            }

            //asociar el documento con el registro de transferencia original
            TransferInventory::find($transferInventory->id)->update(['document_foxcon_id' => $foxconDocuments->id]);
        }

        if($epps->count() > 0){
            $config =  ConfigurarionHeadquarterFoxcon::where('warehouse_id',$transferInventory->warehouse_id)
                ->where('document_type_id',3)->orderBy('created_at', 'desc')->first();
            $configFoxcon=$config;


            $numero=$this->getNumberFoxcon($configFoxcon->consecutive+1);
            $foxconDocument2 = FoxconDocument::create([
                "documento" => $configFoxcon->nomenclature,
                "numero" => $numero,
                "timestamp" => now(),
                "grabacion" => now(),
                "sync" => 0,
                "nit" => $this->getNitFoxconByWid($transferInventory->warehouse_id),
                "usuario" => $this->getUserIdFoxcon($transferInventory->user_id),
            ]);
            $configFoxcon->consecutive = $numero;
            $configFoxcon->save();
            foreach ($epps as $indx => $content) {
                    $foxconDocument2->details()->create([
                            'documento'          => $configFoxcon->nomenclature,
                            'numero'             => $numero,
                            'timestamp'          => now(),
                            'producto'           => $this->getProductFoxcon($content->product_id),
                            'bodega'             => $this->getWarehouseFoxconByWid($transferInventory->warehouse_id),
                            'cantidad'           => $content->transfer_quantity,
                            'nit'                => $this->getNitFoxconByWid($transferInventory->warehouse_id),
                            'tipoes'             => "S",
                            'plot'               => strtoupper($content->plot)

                    ]);

            //descontar del inventario de la bodega principal
            $productInventorysDeduct = Inventory::
            where('warehouse_id',$transferInventory->warehouse_id)
            ->where('product_id',$content->product_id)
            ->get();

            $product=Product::find($content->product_id);
            if(count($productInventorysDeduct) === 0) abort(406,"Stock insuficiente del producto $product->name");
            foreach($productInventorysDeduct as $productInventoryDeduct){
                $decreseValue=0;
                $decreseValue=$content->transfer_quantity;
                $productInventoryDeduct->stock -= $decreseValue;
                $productInventoryDeduct->save();
                $detailsInventori=$productInventoryDeduct->detailInventories->where('plot',$content->plot);
                
                foreach ($detailsInventori as $inventorydet){
                    $stock_old=$inventorydet->stock-$decreseValue;
                    $inventorydet->stock=$stock_old;
                    $inventorydet->save();
                    if($inventorydet->stock < 0){
                        abort('406', "La cantidad a tranferir del producto '".$productInventoryDeduct->product->name."', NO debe ser mayor al Stock del lote '".$inventorydet->plot."'");
                    }
                }
               if($productInventorysDeduct->first()->stock < 1){ 
                    $productInventoryDeduct->delete();
                    $detailsInventori->each->delete();
                }
            }
        }
        TransferInventory::find($transferInventory->id)->update(['epp_document_foxcon_id' => $foxconDocument2->id]);

        }
    }

    public function expense(Request $request){

        try{

            DB::beginTransaction();
            //el usuario
            $user = User::find($this->getUserIDToken($request));

            
            $foxconDocument= null;

            //Validar si la integración con foxcon está activa
            if(\App\Integration::find(1)->enabled && 
            in_array(
                \App\Headquarter::find(
                    $user->headquarter_id
                )->id , \App\Integration::find(1)->headquarters
            )){
                $scheduling = Scheduling::where('crm_schedule_id',$request->crm_schedule_id)->orderBy('created_at','desc')->first();
                $request->merge(["observation_foxcon" => $scheduling->name ?? null]);
                $foxconDocument =   $this->saveFoxconDocumentsExpenses($request,$user->id, $request->warehouse_id);
            }
            
            

            //se utiliza firstOrCreate para evitar duplicados
            $inventoryExpense = InventoryExpense::updateOrCreate(
                [
                    "id" => $request->input("id")  
                ],
                [
                    "inventory_detail_id"   => $request->input("inventory_detail_id"),
                    "warehouse_id"          => $request->input("warehouse_id"),
                    "product_id"            => $request->input("product_id"),
                    "control_id"            => $request->input("control_id"),
                    "crm_schedule_id"       => $request->input("crm_schedule_id"),
                    "foxcon_document_id"    => $foxconDocument,
                    "headquarter_id"        => $user->headquarter_id,
                    "user_id"               => $user->id,
                    "code"                  => $request->input("code"),
                    "plot"                  => $request->input("plot"),
                    "date_expiration"       => $request->input("date_expiration"),
                    "quantity"              => $request->input("stock"),
                    "health_register"       => $request->input("health_register"),
                    "product_report"        => $request->input("product_report"),
                    "dose_quantity"         => $request->input("dose_quantity"),   
                    "created_at"            => $request->input("created_at"),
                ]);

                //código para crear el documento en las tablas de foxcon

                //código para asociar el documento foxcon al consumo del soft

                //código temporal para descontar del inventario del soft 
                //(temporal hasta que comience a funcionar lo de foxcon)

                //primero el inventario hijo
                $inventory_detail_id = InventoryDetail::find($request->input('inventory_detail_id'));
                if($inventory_detail_id){

                    $stock = intval($inventory_detail_id->stock) - intval($request->input('stock'));
                    $inventory_detail_id->stock = $stock;
                    $inventory_detail_id->save();

                    //actualizar inventario padre tambien
                    $inventory = Inventory::find($inventory_detail_id->inventory_id);
                    if($inventory != null){
                        $stock2 = intval($inventory->stock) - intval($request->input('stock'));
                        $inventory->stock = $stock2;
                        $inventory->save();
                    }
                }else{
                    DB::rollback();
                    return $this->error('El inventario a intervenir ya no se encuentra disponible',500,["trace" => ['id' =>$request->input('inventory_detail_id')], 'success' => false]);
                }

                DB::commit();
                return $this->success(true); 
        }catch(\Exception $ex){
            DB::rollBack();
        return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);
        
        }

    }

    public function deleteExpense(Request $request){

        try{

            DB::beginTransaction();
            //el usuario
            $user = User::find($this->getUserIDToken($request));

            //buscar y eliminar el consumo reportado
            $inventoryExpense = InventoryExpense::find($request->input("id"));
            $successDelete = optional($inventoryExpense)->delete();
            $inventoryExpenseFoxcon=optional(optional($inventoryExpense)->foxconDocument);
            $successDeleteFoxcon=false;
            if($inventoryExpenseFoxcon->sync == 0){
                $successDeleteFoxcon=$inventoryExpenseFoxcon->delete();
            }
            if($successDeleteFoxcon){
                if($successDelete){
                    //reestablecer el stock
                    $inventory_detail_id = InventoryDetail::find($request->input('inventory_detail_id'));
                    if($inventory_detail_id){

                        $stock = $inventory_detail_id->stock + $request->input('stock');
                        $inventory_detail_id->stock = $stock;
                        $inventory_detail_id->save();

                        //actualizar inventario padre tambien
                        $inventory = Inventory::find($inventory_detail_id->inventory_id);
                        $stock2 = $inventory->stock + $request->input('stock');
                        $inventory->stock = $stock2;
                        $inventory->save();
                    }else{
                        DB::rollback();
                        return $this->error('El inventario a intervenir ya no se encuentra disponible',500,["trace" => ['id' =>$request->input('inventory_detail_id')], 'success' => false]);
                    }
                    
                }else{
                    DB::rollback();
                        return $this->error('El consumo que se desea eliminar ya no existe en el sistema',500,["trace" => ['id' =>$request->input('inventory_detail_id')], 'success' => false]);
                }
            }else{
                DB::rollback();
                    return $this->error('El consumo que desea eliminar, ya fue sincrinizado en foxcon',500,["trace" => ['id' =>$request->input('inventory_detail_id')], 'success' => false]);
            }

           
            DB::commit();
            return $this->success(['success' => true]);
        }catch(\Exception $ex){
            DB::commit();
                return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);
        }

    }

    public function getFoxconDocuments(Request $request)
    {  
        if(!$request->filled('status')){
            return response()->json(["success"=>false, "message" => "No se proporcionó el parámetro status", "data" => []],200);
        }

        $foxconDocumentObject =  FoxconDocument::with("details");

        switch($request->input('status')){
            case 'synchronized':
                $foxconDocumentObject->where('sync',1);
                break;
            case 'unsynchronized':
                $foxconDocumentObject->where('sync',0);
                break;
            case 'any':
                break;
            default:
                return response()->json(["success"=>false, "message" => "EL valor proporcionado el parámetro status no es válido", "data" => []],200);
                break;
        }

        $documents = $foxconDocumentObject->get();
        return response()->json(["success"=>true, "data" => ['documents' => $documents]],200);
    }

    public function setAsSynchronized(Request $request,$id)
    {  
        try {
            DB::beginTransaction();
                $foxconDocument = FoxconDocument::find($id);
                if(isset($foxconDocument)){
                    if($foxconDocument->sync == 1) return response()->json(["success"=> true, "message" => "El documento $id, ya fue syncronizado.","data" => []],200);
                    $foxconDocument->sync = 1;
                    $foxconDocument->save();
                }else{
                    return response()->json(["success"=> false, "message" => "El documento $id no existe.","data" => []],200);
                }
            DB::commit();
            return response()->json(["success"=> true, "message" => "El documento $id, se ha sincronizado. ","data" => []],200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["success"=> false, "error" => ['message' => $e->getMessage(), 'trace' => $e->getTrace() ]],500);
        }
    }

    public function setAllAsSynchronized(Request $request)
    {  
        try {
            DB::beginTransaction();
                $cant = FoxconDocument::where('sync',0)->count();
                FoxconDocument::where('sync',0)->update(["sync" => 1]);
            DB::commit();
            return response()->json(["success"=> true, "message" => "$cant documentos sincronizados.","data" => []],200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["success"=> false, "error" => ['message' => $e->getMessage(), 'trace' => $e->getTrace() ]],500);
        }
    }

    public function saveNewFoxconDoc(Request $request)
    {  
        try {
            DB::beginTransaction();

                //validar que el documento no exista 
                $fx = FoxconDocument::where('documento', $request->documento)
                ->where('numero', $request->numero)->first();
                
                $action = "create";

                

                if($fx != null){
                    
                    //revertir lo valores en el inventario
                    if($fx->anulado != 1) {
                        
                        $this->deletePreviousInventory($request, $fx);
                    }

                    if($request->anulado != 1){
                        //volver a poner los valores actualizados
                        
                        $this->recreateInventory($request, $fx);
                    }

                    if($request->anulado == 1){
                        $request->merge(['observation' => "Documento anulado desde Foxcon"]);
                    }else{
                        $request->merge(['observation' => "Documento actualizado desde Foxcon"]);
                    }
                    
                    $fx->fill($request->all());
                    $fx->sync = 1;
                    $fx->save();

                    foreach ($request->input('detalles') as $detalles){

                        if(!isset($detalles['factor'])){
                            return response()->json(["success"=> false, "error" => ['message' => 'El factor de conversión no ha sido enviado para el producto ' . $detalles['producto'] ]],500);
                        }else{
                            //validar que el factor de conversión sea un número
                            if(!is_numeric($detalles['factor'])){
                                return response()->json(["success"=> false, "error" => ['message' => 'El factor de conversión debe ser un número para el producto ' . $detalles['producto'] ]],500);
                            }
                        }

                        $detalle = new Request($detalles);
                        $fx->details()->update($detalles);
                        
                    }

                    $action = "update";
                    //return response()->json(["success"=> true, 'message' => "El documento ".$request->documento." ".$request->numero. " ya existe.", "data" => ['id' =>$fx->id]],500);

                }else{

                    if($request->anulado == 1){
                        $request->merge(['observation' => "Documento anulado desde Foxcon"]);
                    }else{
                        $request->merge(['observation' => "Documento creado desde Foxcon"]);
                    }

                    $fx = new FoxconDocument();
                    $fx->fill($request->all());
                    $fx->sync = 1;
                    $fx->save();

                    //sincronizar los detalles
                    foreach ($request->input('detalles') as $detalles){
                        $detalle = new Request($detalles);
                        $fx->details()->create($detalle->all());

                        if($request->anulado != 1){
                            $this->saveDocumentInventory($detalle, $fx);
                        }
                        
                    }

                }
                

            DB::commit();
            return response()->json(["success"=> true, "message" => $action == 'update' ? ($request->anulado ==  1 ? "Documento anulado correctamente": "Documento existente actualizado correctamente") : ($request->anulado ==  1 ? "Se ha creado un documento en estado anulado" :"Documento creado correctamente"),"data" => ['id' =>$fx->id]],200);
       
       
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["success"=> false, "error" => ['message' => $e->getMessage(), 'trace' => $e->getTrace() ]],500);
        }
    }

    private function saveDocumentInventory(Request $request, $fx){

       

        $documentTypeFoxcon =  ConfigurarionHeadquarterFoxcon::where('nomenclature',$request->documento)->first();
        if(is_null($documentTypeFoxcon)){
            abort(406, 'El documento foxcon con nomenclatura '.$request->documento . ' no existe en el soft o no ha sido asociado aún.');
            //return response()->json(["success"=> false, "error" => ['message' => 'La sede de palmera con nit '.$request->nit . ' no existe en el soft o este nit no ha sido asociado aún.', 'trace' => null ]],406);
        }

        //intentar obtener el headquarter por el número de documento
        $warehouse = Warehouse::where('code_foxcon', $request->bodega)->first();
        if(is_null($warehouse)){
            abort(406, 'La bodega de palmera con codigo foxcon '.$request->bodega . ' no existe en el soft o este código no ha sido asociado aún.');
            //return response()->json(["success"=> false, "error" => ['message' => 'La bodega con código foxcon '.$request->bodega . ' no existe en el soft o este código no ha sido asociado aún.', 'trace' => null ]],406);
        }

        $headquarter = null;
        if($warehouse->warehousable_type == 'App\Headquarter'){
            $headquarter = $warehouse->warehousable;
        }else{

            //intentar obtener el headquarter por le tipo de documento
            if(isset($documentTypeFoxcon->warehouse)){
                $headquarter = $documentTypeFoxcon->warehouse->warehousable;
                if(is_null($headquarter)){
                    $headquarter    = Headquarter::where("nit",$request->nit)->first();
                    if(is_null($headquarter)){
                        abort(406, 'La sede de palmera con nit '.$request->nit . ' no existe en el soft o este nit no ha sido asociado aún.');
                        //return response()->json(["success"=> false, "error" => ['message' => 'La sede de palmera con nit '.$request->nit . ' no existe en el soft o este nit no ha sido asociado aún.', 'trace' => null ]],406);
                    }
                }
            }else{
                abort(406, 'El documento foxcon con nomenclatura '.$request->documento . ' no ha sido asociado a una bodega o la bodega destino ha sido eliminada');
            }
            
        }
        
        $producto       = Product::where('code',$request->producto)->first();

        if(is_null($producto)){
            abort(406, 'El producto de palmera con código foxcon '.$request->producto . ' no existe en el soft o este código no ha sido asociado aún.');
            //return response()->json(["success"=> false, "error" => ['message' => 'La producto con código foxcon '.$request->producto . ' no existe en el soft o este código no ha sido asociado aún.', 'trace' => null ]],406);
        }

        $inventory  =  Inventory::withTrashed()->where('warehouse_id', $warehouse->id)->where('product_id',$producto->id)->first();
        
        if(isset($inventory)){

            $inventory->stock = $inventory->stock+(intval($request->cantidad) * intval($request->factor));
            $inventory->save();

            // $detail = optional($inventory->detailInventories)->last();
            // if(isset($detail)){
            //     $detail->stock = $detail->stock + $request->cantidad;
            //     $detail->save();
            // }

            $dateExpiration=new Carbon();

                $newStock = 0;
                $inventoryDetail = $inventory->detailInventories()->withTrashed()->where('plot', 'N/A')->first();
                if($inventoryDetail){
                    $newStock = intval($inventoryDetail->stock) + (intval($request->cantidad) * intval($request->factor));
                }


                $inventory->detailInventories()->updateOrCreate(
                    [
                        "warehouse_id"      => $warehouse->id,
                        "product_id"        => $producto->id,
                        "plot"              => 'N/A',
                    ],
                    [
                        "stock"             => $newStock,
                        "code"              => 'N/A',
                        "date_expiration"   => $dateExpiration->addYear(),
                        "foxcon_document_id" => $fx->id 
                    ]
                );

        }else{
            if(isset($headquarter) && isset($warehouse) && isset($producto)){
                $inventorySave=$warehouse->inventory()->create([
                    "headquarter_id"    => $headquarter->id,
                    "product_id"        => $producto->id,
                    "stock"             => (intval($request->cantidad) * intval($request->factor))
                ]);

                $dateExpiration=new Carbon();
                $inventorySave->detailInventories()->create([
                    "warehouse_id"      => $warehouse->id,
                    "product_id"        => $producto->id,
                    "stock"             => (intval($request->cantidad) * intval($request->factor)),
                    "code"              => 'N/A',
                    "plot"              => 'N/A',
                    "date_expiration"   => $dateExpiration->addYear(),
                    "foxcon_document_id" => $fx->id
                ]);
            }
        }
    }

    private function recreateInventory(Request $request, FoxconDocument $fx){

        foreach ($request->detalles as $key => $detail) {
            $detail = new Request($detail);
            $warehouse = Warehouse::where('code_foxcon', $detail->bodega)->first();
            $product = AppProduct::where('code', $detail->producto)->first();

            if(!$product) {
                abort(406, 'El producto con código '.$detail->producto. ' no existe en el soft o no ha sido asociado aún.');
            }

            if($warehouse){

                $inventoryDetail  =  InventoryDetail::
                    withTrashed()
                    ->where('warehouse_id', $warehouse->id)
                    ->where('product_id',$product->id)
                    ->where('plot',$detail->plot ?? 'N/A')
                    ->first();

                if($inventoryDetail){
                
                    if($detail->tipoes == 'E'){
                        $inventoryDetail->stock +=  (intval($detail->cantidad) * intval($detail->factor));
                    }else{
                        $inventoryDetail->stock -=  (intval($detail->cantidad) * intval($detail->factor));
                    }

                    if($inventoryDetail->stock > 0){
                        $inventoryDetail->deleted_at = null;
                    }

                    $inventoryDetail->save();

                    $inventory = Inventory::withTrashed()->find($inventoryDetail->inventory_id);

                    if($detail->tipoes == 'E'){
                        $inventory->stock +=  (intval($detail->cantidad) * intval($detail->factor));
                    }else{
                        $inventory->stock -=  (intval($detail->cantidad) * intval($detail->factor));
                    }

                    if($inventory->stock > 0){
                        $inventory->deleted_at = null;
                    }

                    $inventory->save();

                }else{

                    $headquarter = null;
                    if($warehouse->warehousable_type == 'App\Headquarter'){
                        $headquarter = $warehouse->warehousable;
                    }else{

                        //intentar obtener el headquarter por le tipo de documento
                        $documentTypeFoxcon =  ConfigurarionHeadquarterFoxcon::where('nomenclature',$request->documento)->first();
                        if(is_null($documentTypeFoxcon)){
                            abort(406, 'El documento foxcon con nomenclatura '.$request->documento . ' no existe en el soft o no ha sido asociado aún.');
                            //return response()->json(["success"=> false, "error" => ['message' => 'La sede de palmera con nit '.$request->nit . ' no existe en el soft o este nit no ha sido asociado aún.', 'trace' => null ]],406);
                        }
                        if(isset($documentTypeFoxcon->warehouse)){
                            $headquarter = $documentTypeFoxcon->warehouse->warehousable;
                            if(is_null($headquarter)){
                                $headquarter    = Headquarter::where("nit",$request->nit)->first();
                                if(is_null($headquarter)){
                                    abort(406, 'La sede de palmera con nit '.$request->nit . ' no existe en el soft o este nit no ha sido asociado aún.');
                                    //return response()->json(["success"=> false, "error" => ['message' => 'La sede de palmera con nit '.$request->nit . ' no existe en el soft o este nit no ha sido asociado aún.', 'trace' => null ]],406);
                                }
                            }
                        }else{
                            abort(406, 'El documento foxcon con nomenclatura '.$request->documento . ' no ha sido asociado a una bodega o la bodega destino ha sido eliminada');
                        }
                        
                    }
                    //crear primero el documento inventory
                    $inventory = Inventory::create([
                        "headquarter_id" => $headquarter->id,
                        "warehouse_id" => $warehouse->id,
                        "product_id" => $product->id,
                        "stock" => (intval($detail->cantidad) * intval($detail->factor))
                    ]);

                    $inventory->detailInventories()->create([
                        "warehouse_id" => $warehouse->id,
                        "product_id" => $product->id,
                        "code" => "N/A",
                        "plot" => "N/A",
                        "stock" => (intval($detail->cantidad) * intval($detail->factor)),
                        "foxcon_document_id" => $fx->id,
                    ]);


                }
            }
        }
       
    }

    private function deletePreviousInventory(Request $request, FoxconDocument $fx){

        foreach ($fx->details as $key => $detail) {
            $warehouse = Warehouse::where('code_foxcon', $detail->bodega)->first();
            $product = AppProduct::where('code', $detail->producto)->first();

            if($warehouse){
                $inventoryDetail  =  InventoryDetail::
                    withTrashed()
                    ->where('warehouse_id', $warehouse->id)
                    ->where('product_id',$product->id)
                    ->where('plot',$detail->plot)
                    ->first();

                if($inventoryDetail){
                
                    if($detail->tipoes == 'E') 
                    {
                        $inventoryDetail->stock -= (intval($detail->cantidad) * intval($detail->factor));
                    }else{
                        $inventoryDetail->stock += (intval($detail->cantidad) * intval($detail->factor));
                    }

                    if($inventoryDetail->stock > 0){
                        $inventoryDetail->deleted_at = null;
                    }

                    $inventoryDetail->save();

                    $inventory = Inventory::withTrashed()->find($inventoryDetail->inventory_id);
                    
                    if($detail->tipoes == 'E'){
                        $inventory->stock -=  (intval($detail->cantidad) * intval($detail->factor));
                    }else{
                        $inventory->stock +=  (intval($detail->cantidad) * intval($detail->factor));
                    }

                    if($inventory->stock > 0){
                        $inventory->deleted_at = null;
                    }
                    $inventory->save();

                    //dd($warehouse->toArray(), $product->toArray(), $inventoryDetail->toArray());
                }
            }
        }
       
    }

    private function getDocumentFoxcon($user_id){ 
        $warehouse= Warehouse::where('warehousable_type','App\User')->where('warehousable_id',$user_id)->first();
        $result = optional(optional(optional(optional($warehouse)->parentt)->configurationFoxcon)->where('document_type_id', 3))->last();
        if(!$result) abort(406,"Para registrar la transacción en Foxcon, debe configurar los Consumos de documentos de foxcon, para la bodega > ".optional(optional($warehouse)->parentt)->name ?? '');
        return $result;
    }

    private function getDocumentFoxconById($id){ 
       return ConfigurarionHeadquarterFoxcon::find($id);
    }

    private function getNitFoxcon( $id){ 
        $warehouse= Warehouse::where('warehousable_type','App\User')->where('warehousable_id',$id)->first();
        $nit=optional(optional(optional($warehouse)->warehousable)->headquarter)->nit;
        if(!$nit) abort(406,"Para registrar la transacción en Foxcon, debe configurar el NIT para la sede > ".optional(optional(optional($warehouse)->warehousable)->headquarter)->name);
        return $nit;
    }

    private function getNitFoxconByWid( $id){ 
        $warehouse= Warehouse::find($id);
        
        if($warehouse->warehousable_type =='App\Headquarter'){
            
            $nit=optional(optional($warehouse)->warehousable)->nit;
            $sede = optional(optional($warehouse)->warehousable)->name;
        }else{
            $nit=optional(optional(optional($warehouse)->warehousable)->headquarter)->nit;
            $sede = optional(optional(optional($warehouse)->warehousable)->headquarter)->name;
        }
        
        if(!$nit) abort(406,"Para registrar la transacción en Foxcon, debe configurar el NIT para la sede > ".$sede);
        return $nit;
    }

    private function getProductFoxcon($id){ 
        $product= Product::find($id);
        $code = optional($product)->code;
        if(!$code) abort(406,"Para registrar la transacción en Foxcon, debe configurar el codigo Foxcon para el producto > ". optional($product)->name);
        return $code;
    }

    private function getUserIdFoxcon($id){ 
            $user=User::find($id);
            $id_foxcon=optional($user->headquarter)->foxcon_user_id;
            $name=optional($user->headquarter)->name;
        if(!$id_foxcon) abort(406,"Para registrar la transacción en Foxcon, debe configurar el id Foxcon de compras para la sede  > ".$name);
        return $id_foxcon;
    }

    private function getWarehouseFoxcon($id){ 
        $warehouse= Warehouse::where('warehousable_type','App\User')->where('warehousable_id',$id)->first();
        $code_foxcon = optional($warehouse)->code_foxcon;
        if(!$code_foxcon) abort(406,"Para registrar la transacción en Foxcon, debe configurar el codigo foxcon para la bodega > ".optional($warehouse)->name);
        return $code_foxcon;
    }

    private function getWarehouseFoxconByWid($id){ 
        $warehouse= Warehouse::find($id);
        $code_foxcon = optional($warehouse)->code_foxcon;
        if(!$code_foxcon) abort(406,"Para registrar la transacción en Foxcon, debe configurar el codigo foxcon para la bodega > ".optional($warehouse)->name);
        return $code_foxcon;
    }

    private function getNumberFoxcon($number){ 
        $lenght=strlen($number);
        $rest=6-$lenght;
        $val='';
        for ($i=0; $i < $rest; $i++) { 
            $val.='0';
        }
        return $val.$number;
    }

    //PERMITE GUARDAR LA TRANSFERENCIA EN LA TABLA DE FOXCON CUANDO SE REALIZA LA TRANFERENCIA DESDE UNA BODEGA A UN TECNICO LA CUAL NO NECESITA DE APROBACION
    private function saveFoxconDocumentsExpenses(Request $request,$user_id, $warehouse_id = null){

        $foxconDocuments=collect();
        $inventoryExpense = InventoryExpense::find($request->id);
        if(isset($inventoryExpense)){
            $documentFoxcon=optional($inventoryExpense->foxconDocument);
            if($documentFoxcon->sync == "1") return abort(406,'El inventario a intervenir ya no se encuentra disponible, porque fue syncronizado por Foxcon');
            if(isset($documentFoxcon)){
                $detailDocuments=optional($documentFoxcon->details)->first();
                if(isset($detailDocuments)){
                    $detailDocuments->producto = $this->getProductFoxcon($request->product_id);
                    $detailDocuments->cantidad = $request->stock;
                    $detailDocuments->save();
                }
                return $documentFoxcon->id;
            }
        }else{
            $configFoxcon=$this->getDocumentFoxcon($user_id);
            $numero=$this->getNumberFoxcon($configFoxcon->consecutive+1);
             $configFoxcon->consecutive = $numero;
             $configFoxcon->save();
            $foxconDocuments = FoxconDocument::create([
                "documento" => $configFoxcon->nomenclature,
                "numero" => $numero,
                "timestamp" => $request->created_at ?? now(),
                "grabacion" => $request->created_at ?? now(),
                "sync" => 0,
                "usuario" => $this->getUserIdFoxcon($user_id),
                "nit" => $this->getNitFoxcon($user_id),
                "created_at" => $request->created_at ?? now(),
                "updated_at" => $request->created_at ?? now(),
                "observation" => $request->observation_foxcon
            ]);
            
            $foxconDocuments->details()->create([
                    'documento'          => $configFoxcon->nomenclature,
                    'numero'             => $numero,
                    'timestamp'          => $request->created_at ?? now(),
                    'producto'           => $this->getProductFoxcon($request->product_id),
                    'bodega'             =>  $warehouse_id ? $this->getWarehouseFoxconByWid($warehouse_id) :  $this->getWarehouseFoxcon($user_id),
                    'cantidad'           => $request->stock,
                    'nit'                => $this->getNitFoxcon($user_id),
                    'tipoes'             => "S",
                    'plot'               => strtoupper($request->plot)
            ]);
        }
        return optional($foxconDocuments)->id;
    }

    public function create()
    {

    }


    public function store(Request $request)
    {
    	
    }


    public function show( $userTracking)
    {
        dd("función Show");
    }


    public function edit( $userTracking)
    {
        //
    }


    public function update(Request $request,  $userTracking)
    {
        //
    }


    public function destroy( $userTracking)
    {
        //
    }
}
