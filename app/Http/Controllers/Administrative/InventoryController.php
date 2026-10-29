<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Administrative;

use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use App\Models\Programming;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Warehouse;
use App\Headquarter;
use App\Cellar;
use App\State;
use App\City;
use App\ConfigurarionHeadquarterFoxcon;
use App\OrderPurchase;
use App\Zone;
use App\Product;
use App\ProductWarehouseConfig;
use App\Purchase;
use App\PurchaseProductDetails;
use App\TransferInventory;
use App\TransferInventoryDetail;
use App\OrderPurchaseDetail;
use App\Rules\InventoryLessThanOrEquals;
use App\GeneralOrderPurchase;
use App\DetailGeneralOrderPurchase;
use App\ConfirmationTransferInventory;
use App\DetailConfirmationTransfer;
use App\DocumentTypeFoxcon;
use App\FoxconDocument;
use App\GiftInventory;
use App\Inventory;
use App\InventoryDetail;
use App\SharedMechasnims;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

/**
 * Description of InventoryController
 *
 * @author json
 */
class InventoryController extends Controller{
    
    public $msgError = "No se pudo realizar la acción solicitada.";
    public $msgHeadquarter = "Bodega agregada correctamente.";
    public $msgSuccessProductWarehouse = "Configuración agregada correctamente.";
    public $msgSuccessPurchase = "Compra agregada correctamente.";
    public $msgSuccessInventory = "Tranferencia realizada correctamente.";
    public $msgSuccessOrderPuchase = "Orden de compra realizada correctamente.";
    public $msgSuccessGeneralOrderPuchase = "Orden de compra general realizada correctamente.";
    public $updateHeadquarter = "Bodega actualizada correctamente."; 
    public $updatePurchase = "Compra actualizada correctamente.";
    public $updateEditINventory = "Inventario actualizado correctamente.";
    public $msgSuccessConfirmationTransfer = "Confirmacion general realizada correctamente.";
    public $updateProductWarehouse = "Configuración actualizada correctamente.";
    public $updateTransferInventory = "Tranferencia actualizada correctamente.";
    public $updateOrderPuchase = "Orden de compra actualizada correctamente.";
    public $msgWarehouseDeleted = "Bodega eliminada correctamente.";
    public $msgDeletedSopping = "Compra eliminada correctamente.";
    public $msgDeletedSettings = "Configuración eliminada correctamente.";
    public $msgDeletedTransferInventory = "Tranferencia eliminada correctamente.";
    public $msgDeletedConfirmationTransfer = "Confirmacion de tranferencia eliminada correctamente.";
    public $msgDeletedOrderPuchase = "Orden de compra eliminada correctamente.";
    public $msgDeletedOrderPuchaseDetail = "Detalle de orden de compra eliminada correctamente.";
    public $msgDeletedPuchaseDetail = "Detalle de compra eliminada correctamente.";
    public $msgDeletedTransferDetail = "Detalle de transferencia eliminada correctamente.";
    public $msgDeletedGeneralOrderPuchase = "Compra eliminada correctamente.";
    public $msgWarehouseChilds = "No puede eliminar esta bodega porque posee bodegas hijas, elimine primero las bodegas hijas.";
    
    public function index(){      
        return view('inventory.index');
    }

    public function foxconDocs(Request $request){ 

        if($request->filled('bnacional')){
            $bnacional = true;
        }else{
            $bnacional = false;
        }

        list($documents, $heaquarterWarehouseId, $footer) = $this->foxconDocsList($request);   
        return view('inventory.foxcon', compact(['documents','heaquarterWarehouseId','footer'],'bnacional'));
    }

    public function foxconDocsList(Request $request){
        if($request->filled('bnacional')){
            $heaquarterWarehouseId = 1;
        }else{
            $heaquarterWarehouseId = Headquarter::find(session('headquarter_id'))->warehouse->id;
        }

        $documentTypeConfigsObj = ConfigurarionHeadquarterFoxcon::where('warehouse_id', $heaquarterWarehouseId);

        
        if($request->filled('documento')){
            $documentTypeConfigsObj->where('id',$request->input('documento'));
        }
           
        $documentTypeConfigs =  $documentTypeConfigsObj->get();
    
        $documentsObj = FoxconDocument::with(['details.product','details.warehouse','document.type'])
            ->whereIn('documento', $documentTypeConfigs->pluck('nomenclature'))
            ->orderBy('grabacion','desc');

        if($request->filled('start_date')){
            $documentsObj->whereBetween('grabacion',[ Carbon::parse($request->input('start_date'))->startOfDay(), Carbon::parse($request->input('end_date'))->endOfDay() ]);
        }

        if($request->filled('numero')){
            $documentsObj->where('numero','like', "%{$request->input('numero')}%");
        }


        if($request->filled('bodega')){
            $documentsObj->whereHas('details',function($details) use($request){
                $details->whereHas('warehouse', function($warehouse) use($request){
                    $warehouse->where('code_foxcon', 'like', "%{$request->input('bodega')}%")
                    ->orWhere('name', 'like', "%{$request->input('bodega')}%");
                });
            });
        }

        if($request->filled('producto')){
            $documentsObj->whereHas('details',function($details) use($request){
                $details->whereHas('product', function($product) use($request){
                    $product->where('code', 'like', "%{$request->input('producto')}%")
                    ->orWhere('name', 'like', "%{$request->input('producto')}%");
                });
            });
        }
            
        $documents = $documentsObj->paginate($request->filled('perpage') ? $request->input('perpage') : 10); 
              
        $footer = view('inventory.sub.foxcon_list_footer', compact('documents'))->render();

        if($request->ajax()){
            $view = view('inventory.sub.foxcon_list', compact('documents','heaquarterWarehouseId'))->render();
            return response(['success' => true, 'view' => $view, 'footer' => $footer]);
        } else {
            return [$documents,$heaquarterWarehouseId, $footer];
        }
       

    }

    public function foxconDocsDelete(Request $request, $id){

        try {
            DB::beginTransaction();
             //reestablecer el stock de los documentos anulados
                $foxconDocument = FoxconDocument::find($id);
                foreach ($foxconDocument->details as $key => $detail) {
                    
                    //bodega
                    $warehouse  = Warehouse::where('code_foxcon',$detail->bodega)->orderBy('id','desc')->first();
                    $product    = Product::where('code',$detail->producto)->orderBy('id','desc')->first();
                    
                    $inventoryDetail = InventoryDetail::
                        withTrashed()
                        ->where('plot',$detail->plot)
                        ->where('product_id',$product->id)
                        ->where('warehouse_id',$warehouse->id)
                        ->orderBy('created_at','desc')
                        ->first();
                    
                    $inventory = Inventory::
                        withTrashed()
                        ->where('product_id',$product->id)
                        ->where('warehouse_id',$warehouse->id)
                        ->orderBy('created_at','desc')
                        ->first();

                    if($inventoryDetail != null){

                        if($detail->tipoes == 'E'){

                            $inventoryDetail->stock     = $inventoryDetail->stock - intval($detail->cantidad);
                            $inventory->stock           = $inventory->stock - intval($detail->cantidad);

                        }else if($detail->tipoes == 'S'){

                            $inventoryDetail->stock     =  $inventoryDetail->stock + intval($detail->cantidad);
                            $inventory->stock           =  $inventory->stock + intval($detail->cantidad);

                        }

                        if($inventory->stock > 0){
                            $inventory->deleted_at        = null;
                        }else{
                            $inventory->deleted_at        = Carbon::now()->format('Y-m-d H:i:s');
                        }

                        if($inventoryDetail->stock > 0){
                            $inventoryDetail->deleted_at  = null;
                        }else{
                            $inventoryDetail->deleted_at  = Carbon::now()->format('Y-m-d H:i:s');
                        }

                        
                        $inventoryDetail->save();
                        $inventory->save();
                            
                    }

                }

                $success = FoxconDocument::where('id',$id)->update(["sync" => 0, "anulado" => 1]);
                DB::commit();

                return response(['success' => $success, 'message' => $success ? 'Documento anulado correctamente': 'Ya no se puede anular este documento']);
        

        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => $ex->getMessage()]);
        }

       
    }

    public function technical(){        
        return view('inventory.technical');
    }

    public function technicalList(){

        $warehouse = Auth::user()->warehouse;

        $inventories1 = Inventory::with(['detailInventory.product','headquarter','warehouse','detailInventories'])
        ->where('warehouse_id', $warehouse->id )
        ->orderBy('id', 'desc')
        ->get();

        foreach ($inventories1 as $inventory){
            if($inventory->stock < 1){
                $inventory->detailInventories->each->delete();
                $inventory->delete();
            }
        }

        $inventories = Inventory::with(['detailInventory.product','headquarter','warehouse','detailInventories'])
        ->where('warehouse_id', $warehouse->id )
        ->orderBy('id', 'desc')
        ->get();

        $list = view('inventory.sub.listInventoryIndividual',compact('inventories','warehouse'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    public function indexGeneralOrderPurchase(){        
        return view('inventory.indexGeneralOrderPurchase');
    }



    public function indexTranferInventory(){        
        return view('inventory.indexConfirmationTransfer');
    }

    //INVENTARIO

    //TABLA DE INVENTARIO INDIVIDUAL
    public function listInventoryIndividual(Request $request) {
        $inventories1 = Inventory::with(['detailInventory.product','headquarter','warehouse','detailInventories'])
        ->where('warehouse_id', $request->warehouse)
        ->orderBy('id', 'desc')
        ->get();

        foreach ($inventories1 as $inventory){
            if($inventory->stock < 1){
                $inventory->detailInventories->each->delete();
                $inventory->delete();
            }
        }
        $warehouse = Warehouse::find($request->warehouse);

        $object = Inventory::with(['detailInventory.product','headquarter','warehouse','detailInventories'])
        ->where('warehouse_id', $request->warehouse);
        
        if($warehouse->warehousable_type == 'App\SharedMechasnims'){
            $inventories=$object->get();
            $inventories=$inventories->groupBy('product_id');
        }else{
            $inventories=$object->orderBy('id', 'desc')->get();;
        }
        $list = view('inventory.sub.listInventoryIndividual',compact('inventories','warehouse'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    //FORMULARIO DE AJUSTE DE INVENTARIO
    public function editInventoryForm(Request $request)
    {
        $id = $request->input('id');
        $deleted = $request->input('deleted');
        $editInventory = Inventory::with(['detailInventories'])->find($id);
        $form = view ('inventory.forms.editInventory',compact(['editInventory','deleted']))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    //FORMULARIO DE ASIGNAR EL CODIGO FOXCON
    public function codeFoxconForm(Request $request)
    {
        $warehouse = Warehouse::find($request->id);
        $form = view ('inventory.forms.codeFoxconForm',compact(['warehouse']))->render();
        return response (['success'=>true, 'view' => $form]);
    }
    //FORMULARIO DE ASIGNAR EL CODIGO FOXCON
    public function codeFoxconSave(Request $request)
    {
        $request->validate(['code_foxcon'=>'required|unique:warehouses'],[],['code_foxcon'=>'Codigo Foxcon']);
        try {
            DB::beginTransaction();

            $warehouse = Warehouse::find($request->id);
            $success=$warehouse->update(['code_foxcon'=>$request->code_foxcon]);
    
            DB::commit();
            if($success) return response (['success'=>true, 'message' => 'Codigo asignado correctamente']);
            else return response (['success'=>false, 'message' => 'Error al asignar el codigo foxcon']);
        } catch (\Exception $th) {
            DB::rollback();
            return response (['success'=>false, 'message' => 'Error al asignar el codigo foxcon', "trace" => $th->getTrace()]);
        }
    }


    public function deleteInventory(Request $request, $id)
    {

        try {
            DB::beginTransaction();

            $inventory = Inventory::find($id);
    
            $inventory->detailInventories()->delete();
            $success = $inventory->delete();
    
            DB::commit();
            if($success) return response (['success'=>true, 'message' => 'Producto eliminado correctamente']);
            else return response (['success'=>false, 'message' => 'Error eliminando el producto']);
        } catch (\Exception $th) {
            DB::rollback();
            return response (['success'=>false, 'message' => 'Error eliminando el producto', "trace" => $th->getTrace()]);
        }
        
        
    }
    public function deleteDetailInventory(inventoryDetail $inventoryDetail)
    {
        try {
            DB::beginTransaction();
                $inventory=$inventoryDetail->inventory;
                $inventory->stock-=$inventoryDetail->stock;
                $inventory->save();
                if($inventory->stock < 1) $inventory->delete();
                $success = $inventoryDetail->delete();
            DB::commit();
            if($success) return response (['success'=>true, 'message' => 'Registro eliminado correctamente']);
            else return response (['success'=>false, 'message' => 'Error eliminando el Registro']);
        } catch (\Exception $th) {
            DB::rollback();
            return response (['success'=>false, 'message' => 'Error eliminando el Registro', "trace" => $th->getTrace()]);
        }
        
        
    }
    
    

    //GUARDAR EL FORMULARIO DE AJUSTAR INVENTARIO
    public function updateInventory(Request $request){

        $id        = $request->input('id');
        $inventory  = Inventory::find($id);
   
        
        $success = $this->saveInventory($request, $inventory);


        return response(["success" => $success,"warehouse" => $inventory->warehouse_id, "message" => $success ? $this->updateEditINventory : $this->msgError]);
    }

        //TRANSACCION DEL GUARDADO DE COMPRAS
        public function saveInventory(Request $request, Inventory $inventory){
        
            $this->validateFormEditInventory($request,$inventory);
    
            try {
                DB::beginTransaction();

                $stock_sum=0;
                if($request->has('inventory')){
                    foreach ($request->input('inventory') as $indx => $content) {
                        $content = new Request($content);
                
                            $inventoryEditDetails = InventoryDetail::find($content["id"]);
                            $inventoryEditDetails->stock = $content->input('quantity');
                            $inventoryEditDetails->code = $content->input('code');
                            $inventoryEditDetails->plot = $content->input('plot_input');
                            $inventoryEditDetails->date_expiration = $content->input('date_expiration');
                            $inventoryEditDetails->save();

                            $stock_sum+=$content->input('quantity');
                    }

                    $inventory->stock = $stock_sum;
                    $inventory->save();


                } 
                      
                DB::commit();
                return true;
            } catch (Exception $exc) {
                DB::rollback();
                return false;
            }
    
        }
    
        //VALIDACION DEL GUARDADO DE COMPRAS
        private function validateFormEditInventory(Request $request){
    
            $niceNames = [
                'inventory.*.code' => 'Codigo',
                'inventory.*.plot_input' => 'Lote',
                'inventory.*.quantity' => 'Cantidad',
            ];
                      
            $request->validate([
                'inventory.*.code' => 'required',
                'inventory.*.plot_input' => 'required',
                'inventory.*.quantity' => 'required|numeric',
            ],
            [], $niceNames);
        }
    



    //TABLA DE INVENTARIO
    public function listInventory() {
        $headquearter = Headquarter::with(['warehouse'])->find(session('headquarter_id'));
        $inventories1 = Inventory::with(['detailInventories.product' => function($p){ $p->withTrashed();} ,'headquarter','warehouse'])
        ->orderBy('id', 'desc')
        ->where('warehouse_id', $headquearter->warehouse->id)
        ->get();

        foreach ($inventories1 as $inventory){
            if($inventory->stock < 1){
                $inventory->detailInventories->each->delete();
                $inventory->delete();
            }
        }
        $warehouse = Warehouse::find($headquearter->warehouse->id);

        $inventories = Inventory::with(['detailInventory.product'  => function($p){ $p->withTrashed();},'headquarter','warehouse','detailInventories'])
        ->where('warehouse_id', $headquearter->warehouse->id)
        ->orderBy('id', 'desc')
        ->get();
        
        $list = view('inventory.sub.listInventoryIndividual',compact('inventories','warehouse'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }
    
    public function tree(Request $request){
        
        $startWarehouse = null;
        $bossPurchasing =  $bossPurchasing = Auth::user()->roles->pluck('slug')->contains('boss_purchasing') || Auth::user()->roles->pluck('slug')->contains('root');
        $query = Warehouse::where('enabled',1);
  

            $headquarter = Headquarter::with('warehouse')->find(session('headquarter_id'));
            //$startWarehouse = $headquarter->warehouse->parent;

            if(!$bossPurchasing){
                $startWarehouse = $headquarter->warehouse->parent;
                $startWarehouseId = $headquarter->warehouse->id;
                $query->where(function($q) use($startWarehouseId){
                    $q->where('id', $startWarehouseId)->orWhere('parent', $startWarehouseId);
                });
            }else{
                $query->where(function($q) use($headquarter){
                    $q->whereNull('parent')->orWhere('id', $headquarter->warehouse->id)->orWhere('parent', $headquarter->warehouse->id);
                });
            }
            
        

        if ($request->filled('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%')
            ->orWhere('code_foxcon', 'LIKE', '%' . $request->input('term') . '%');
        }

        $query->orderBy('warehousable_id','asc')->limit(500);

        $warehouses  =  $query->get()
                ->makeHidden(['enabled','created_at','updated_at']);

 

        if ($request->filled('term') && $warehouses) {
            //incluir al parent
            $query->orWhere(function($q) use($warehouses, $bossPurchasing){
                $q->whereIn('id', $warehouses->pluck('id'))->orWhereIn('id', $warehouses->pluck('parent'));
                if($bossPurchasing){
                    $q->orWhere('id', 1);
                }
            });

            $warehouses =  $query->get();

        }

       


        //contruir arbol de bodega
        $tree = $this->makeTree($warehouses,$startWarehouse);
        return response([
            'tree' => $tree,
            'bossPurchasing' => $bossPurchasing, 
            'isFoxconEnabled' => \App\Integration::find(1)->enabled && 
                in_array(
                    \App\Headquarter::find(
                        session('headquarter_id')
                    )->id , \App\Integration::find(1)->headquarters
                    ),
            'default_warehouse' => Warehouse::whereHasMorph(
                'warehousable',
                [Headquarter::class], 
                function($q){ 
                    $q->where('id', session('headquarter_id'));
                }
            )->first()
        ]);
    }
    
    private function makeTree($warehouses=[],$parent = null){
        $branchs = [];
        $subElements = [];
        foreach ($warehouses as $key => $warehouse) {
            
            if($parent == $warehouse->parent){
                
                $branchs[$warehouse->id] = $warehouse;
                
                $daughters = $this->makeTree($warehouses, $warehouse->id);
                if ($daughters) {
                      $branchs[$warehouse->id]["categories"] = $daughters;
                      $warehouses->forget($warehouse->id);
                }
                
                
            }
             
        }
       
        return $branchs;
    }



    public function product(Request $request){
        $query = Product::with('unit');
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
            $query->orWhere('code', 'LIKE', '%' . $request->input('term') . '%');
            
        }
        $products = $query->get();
        return response(['results' => $products]);
    }

    public function documentType(Request $request){
        $query = DocumentTypeFoxcon::select('id','name as text');
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $products = $query->get();
        return response(['results' => $products]);
    }

    public function productFromWarehouse(Request $request, $warehouse){
        $query = Product::with('unit')->whereHas('inventory',function($inventory) use ($warehouse, $request){
            $inventory->where('warehouse_id', $warehouse);
            
            if($request->filled('facturable') && $request->input('facturable') == "1"){
                $inventory->whereHas('detailInventories', function($q){
                    $q->where('facturable', true);
                });
            }else{
                $inventory->whereHas('detailInventories', function($q){
                    $q->where('facturable', false);
                });
            }
        });

        if ($request->input('term')) {
            $query->where(function($sq)use($request){
                $sq->where('name', 'LIKE', '%' . $request->input('term') . '%');
                $sq->orWhere('code', 'LIKE', '%' . $request->input('term') . '%');
            });
        }

        $products = $query->get();
        return response(['results' => $products]);
    }

    public function unit(Request $request, $product){
        
        $unit= Product::with('units')->find($product)->units->name;
        return response(['unit' => $unit]);
    }

/******************************************************************************/
    //INVENTARIO - COMPRAS 
 
    //LISTA DE COMPRAS
    public function listPurchase(Request $request) {
        $warehouse   = Warehouse::find($request->warehouse);
        $purchases = Purchase::with(['detailPurchases.product','headquarter','warehouse'])
                ->orderBy('id', 'desc')
                ->where('warehouse_id', $request->warehouse)
                ->get();
        $list = view('inventory.sub.listPurchase', compact(['warehouse','purchases']))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    //FORMULARIO DE COMPRAS
    public function purchaseInventoryForm(Request $request)
    {
        $warehouse   = Warehouse::find($request->input('warehouse'));
        $key       = uniqid();
        $id = $request->input('id');
        $purchase = Purchase::with(['detailPurchases.product','headquarter'])
        ->find($id);
        $form = view ('inventory.forms.purchaseInventory',compact(['purchase','warehouse','key']))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    //FORMULARIO DE INYECCION DE INVENTARIO
    public function purchaseInyectionForm(Request $request)
    {
        $warehouse   = Warehouse::find($request->input('id'));
        $key       = uniqid();
        $id = null;
        $purchase = Purchase::with(['detailPurchases.product','headquarter'])
        ->find($id);

        $form = view ('inventory.forms.inyectInventory',compact(['purchase','warehouse','key']))->render();
        return response (['success'=>true, 'view' => $form]);
    }
    //FORMULARIO DE INYECCION DE INVENTARIO
    public function giftForm(Request $request)
    {
        $warehouse   = Warehouse::find($request->input('warehouse'));
        $transferInventory = GiftInventory::where('id',$request->input('id'))->get();
        if($request->filled('id') && !$transferInventory) abort(406,"Por favor, recargue la pagina e intente nuevamente");
        $form = view ('inventory.forms.giftInventory',compact(['warehouse','transferInventory']))->render();
        return response (['success'=>true, 'view' => $form]);
    }


    //GUARDAR EL FORMULARIO DE INYECIION
    public function storeInyect(Request $request)
    {
        $purchase = new Purchase;
        $inventory = new Inventory;
        $success = $this->saveInyect($request, $purchase, $inventory);


        return response(["success" => $success, "warehouse" => $request->input('warehouse_id'), "message" => $success ? $this->msgSuccessPurchase : $this->msgError]);
    }

    //TRANSACCION DEL GUARDADO DE COMPRAS
    public function saveInyect(Request $request, Purchase $purchase, Inventory $inventory){
        
        $this->validateFormPurchase($request,$purchase);

        try {
            DB::beginTransaction();

            //Validar si la integración con foxcon está activa
            if(\App\Integration::find(1)->enabled && 
            in_array(
                \App\Headquarter::find(
                    session('headquarter_id')
                )->id , \App\Integration::find(1)->headquarters
            )){
                $this->saveFoxconDocumentsInyect($request);
            }
            
            $purchase->headquarter_id = session('headquarter_id');
            $purchase->warehouse_id = $request->input('warehouse_id');
            $purchase->date_shopping = $request->input('date_shopping');
            $purchase->code_foxcon = strtoupper($request->input('code_foxcon'));
            $purchase->observation = $request->input('observation');
            $purchase->save();

            if($request->has('purchase')){
                foreach ($request->input('purchase') as $indx => $contenido) {
                    $contenido = new Request($contenido);
                    $productInventory = Inventory::where('warehouse_id',$request->input('warehouse_id'))->where('product_id',$contenido->input('product_id'))->first();
                    $num_stock=0;
                    if(filled($productInventory)){
                        $num_stock=optional($productInventory->detailInventories)->where('warehouse_id',$request->input('warehouse_id'))->where('product_id',$contenido->input('product_id'))->where('plot',strtoupper($contenido->input('plot_input')))->pluck('stock')->sum();
                    }
                    if(isset($productInventory->id)){
                        $productInventory->stock += $contenido->input('quantity');
                        $productInventory->save();

                        $productInventory->detailInventory()->updateOrCreate(
                            [ 
                                'warehouse_id'       => $request->input('warehouse_id'),
                                'product_id'         => $contenido->input('product_id'),
                                'plot'               => trim(strtoupper($contenido->input('plot_input'))) != '' ? trim(strtoupper($contenido->input('plot_input'))) : 'N/A',
                            ],
                            [
                                'code'               => trim(strtoupper($contenido->input('code'))) != '' ? trim(strtoupper($contenido->input('code'))) : 'N/A',
                                'date_expiration'    => Carbon::parse($contenido->input('date_expiration'))->format('Y-m-d 00:00:00'),
                                'stock'              => $num_stock+$contenido->input('quantity'),
                            ]);
                    }else{
                        $inventory = Inventory::create([
                            'headquarter_id' => $request->input('headquarter_id'),
                            'warehouse_id' => $request->input('warehouse_id'),
                            'product_id' => $contenido->input('product_id'),
                            'stock' => $contenido->input('quantity')
                        ]);
                        $inventory->detailInventory()->updateOrCreate(
                            [ 
                                'warehouse_id'       => $request->input('warehouse_id'),
                                'product_id'         => $contenido->input('product_id'),
                                'plot'               => trim(strtoupper($contenido->input('plot_input'))) != '' ? trim(strtoupper($contenido->input('plot_input'))) : 'N/A',
                            ],
                            [
                                'code'               =>  trim(strtoupper($contenido->input('code'))) != '' ? trim(strtoupper($contenido->input('code'))) : 'N/A',
                                'date_expiration'    => Carbon::parse($contenido->input('date_expiration'))->format('Y-m-d 00:00:00'),
                                'stock'              => $num_stock+$contenido->input('quantity'),
                            ]);
                    }
                    
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();
            return false;
        }

    }

    private function getDocumentFoxconById( $id){ 
        return  ConfigurarionHeadquarterFoxcon::find($id);
    }


    private function getDocumentFoxconInyec( $id){ 
        //$idd= $id == 1 ? 2 : $id;
        $idd = $id;
        $warehouse= Warehouse::find($idd);
        $document=null;
        if($warehouse->warehousable_type == 'App\Headquarter') $document=$warehouse->configurationFoxcon->where('document_type_id',4)->last();
        if($warehouse->warehousable_type == 'App\User') $document=$warehouse->parentt->configurationFoxcon->where('document_type_id',4)->last();
        if(!$document) abort(406,"Para registrar la transacción en Foxcon, es necesario configurar el Documento foxcon, entrada mercancia para la sede de ". optional(optional($warehouse)->parentt)->name);
        return $document;
    }
    //PERMITE GUARDAR LA TRANSFERENCIA EN LA TABLA DE FOXCON CUANDO SE REALIZA UNA ENTRADA DE MERCANCIA A LA BODEGA
    private function saveFoxconDocumentsInyect(Request $request){
        $configFoxconInyect=$this->getDocumentFoxconById($request->document_type_id);
        $numero=$this->getNumberFoxcon($configFoxconInyect->consecutive+1);
        $foxconDocuments = FoxconDocument::create([
            "documento" => strtoupper($configFoxconInyect->nomenclature),
            "numero" => $numero,
            "timestamp" => now(),
            "grabacion" => now(),
            "sync" => 0,
            "usuario" => $this->getUserIdFoxcon(),
            "nit" => $this->getNitFoxcon($request->warehouse_id),
        ]);

        ConfigurarionHeadquarterFoxcon::find($configFoxconInyect->id)->update(["consecutive" => $numero]);
        // $configFoxconInyect->consecutive = $numero;
        // $configFoxconInyect->save();

       
        if($request->has('purchase')){
            foreach ($request->input('purchase') as $indx => $content) {
                $content = new Request($content);
                
                    $foxconDocuments->details()->create([
                        'documento'                 => strtoupper($configFoxconInyect->nomenclature),
                            'numero'                => $numero,
                            'timestamp'             => now(),
                            'producto'              => $this->getProductFoxcon($content->product_id),
                            'bodega'                => $this->getWarehouseFoxcon($request->warehouse_id),
                            'cantidad'              => $content->quantity,
                            'nit'                   => $this->getNitFoxcon($request->warehouse_id),
                            'tipoes'                => "E",
                            'plot'                  => strtoupper($content->plot_input)
                    ]);
            }
        }
    }
    //GUARDAR EL FORMULARIO DE COMPRAS
    public function storePurchase(Request $request)
    {
        $purchase = new Purchase;
        $inventory = new Inventory;
        $success = $this->savePurchase($request, $purchase, $inventory);


        return response(["success" => $success, "warehouse" => $request->input('warehouse_id'), "message" => $success ? $this->msgSuccessPurchase : $this->msgError]);
    }

    //Actualizar el formulario de compras
    public function updatePurchase(Request $request){
        $id        = $request->input('id');
        $purchase  = Purchase::find($id);
        
        $success = $this->savePurchase($request, $purchase);


        return response(["success" => $success,"warehouse" => $request->input('warehouse_id'), "message" => $success ? $this->updatePurchase : $this->msgError]);
    }

    //TRANSACCION DEL GUARDADO DE COMPRAS
    public function savePurchase(Request $request, Purchase $purchase, Inventory $inventory = null){
        
        if(is_null($inventory)){
            $inventory = new Inventory();
        }
        $this->validateFormPurchase($request,$purchase);
        $idsNewsDetailPurchases=Array();
        try {
            DB::beginTransaction();

            $purchase->headquarter_id = session('headquarter_id');
            $purchase->warehouse_id = $request->input('warehouse_id');
            $purchase->date_shopping = $request->input('date_shopping');
            $purchase->code_foxcon = strtoupper($request->input('code_foxcon'));
            $purchase->observation = $request->input('observation');
            $purchase->save();
            
            if($request->has('purchase')){
                foreach ($request->input('purchase') as $indx => $contenido) {
                    $contenido = new Request($contenido);
                    $productInventory = Inventory::where('warehouse_id',$request->input('warehouse_id'))->where('product_id',$contenido->input('product_id'))->first();

                    $num_stock=0;
                    if(filled($productInventory)){
                        $num_stock=optional($productInventory->detailInventories)->where('warehouse_id',$request->input('warehouse_id'))->where('product_id',$contenido->input('product_id'))->where('plot',strtoupper($contenido->input('plot_input')))->pluck('stock')->sum();
                    }

                    if(isset($productInventory->id)){
                        $productInventory->stock += $contenido->input('quantity');
                        $productInventory->save();

                        $productInventory->detailInventory()->updateOrCreate(
                            [ 
                                'warehouse_id'       => $request->input('warehouse_id'),
                                'product_id'         => $contenido->input('product_id'),
                                'plot'               => strtoupper($contenido->input('plot_input')),
                            ],
                            [
                                'code'               => strtoupper($contenido->input('code')),
                                'date_expiration'    => Carbon::parse($contenido->input('date_expiration'))->format('Y-m-d 00:00:00'),
                                'stock'              => $num_stock+$contenido->input('quantity'),
                            ]);
                    }else{
                        $inventory = Inventory::create([
                            'headquarter_id' => $request->input('headquarter_id'),
                            'warehouse_id' => $request->input('warehouse_id'),
                            'product_id' => $contenido->input('product_id'),
                            'stock' => $contenido->input('quantity')
                        ]);

                        $inventory->detailInventory()->updateOrCreate(
                            [ 
                                'warehouse_id'       => $request->input('warehouse_id'),
                                'product_id'         => $contenido->input('product_id'),
                                'plot'               => strtoupper($contenido->input('plot_input')),
                            ],
                            [
                                'code'               => strtoupper($contenido->input('code')),
                                'date_expiration'    => Carbon::parse($contenido->input('date_expiration'))->format('Y-m-d 00:00:00'),
                                'stock'              => $num_stock+$contenido->input('quantity'),
                            ]);
                    }
                    
                }
            
                if($request->filled('id')){

                    foreach ($purchase->detailPurchases as $purchaseDetail){
                        
                        $productInventori = Inventory::where('warehouse_id',$request->input('warehouse_id'))->where('product_id',$purchaseDetail->product_id)->first();
                        if(filled($productInventori)){
                            $productInventori->stock-=$purchaseDetail->stock;
                            $productInventori->save();
                            if($productInventori->stock < 0){
                                abort(406,"No es posible editar esta compra, porque el Stock del producto, '".$productInventori->product->name."', es menor a la cantidad de la compra");
                            }elseif($productInventori->stock === 0) $productInventori->delete();
                            $detailsInventori=$productInventori->detailInventories->where('warehouse_id',$request->input('warehouse_id'))->where('product_id',$purchaseDetail->product_id)->where('plot',$purchaseDetail->plot)->first();
                            

                            if(filled($productInventori)){
                                $detailsInventori->stock-=$purchaseDetail->stock;
                                $detailsInventori->save();
                                if($detailsInventori->stock < 0){
                                    abort(406,"No es posible editar esta compra, porque el Stock del producto ".$purchaseDetail->product->name." y lote '$purchaseDetail->plot', es menor a la cantidad del inventario actual");
                                }elseif($detailsInventori->stock === 0) $detailsInventori->delete();
                            }else{
                                abort(406,"No es posible editar esta compra, porque el Stock del producto ".$purchaseDetail->product->name." y lote '$purchaseDetail->plot', No existe");
                            }
                        }else{
                            abort(406,"No es posible editar esta compra, porque no existe el producto en la bodega");

                        }

                    }
                }
            }
            if($request->has('purchase')){
                foreach ($request->input('purchase') as $indx => $content) {
                    $content = new Request($content);

                    //verificar si el producto es nuevo o si ya existe
                    if(is_numeric($content->input('product_id'))){
                        $product = Product::find($content->input('product_id'));
                        if(!$product){
                            $product = $this->_createNewProduct($content->input('product_id'));
                        }
                    }else{
                        $product = $this->_createNewProduct($content->input('product_id')); 
                    }
               
                     //actualizar archivo
                     if($content->has('id')){
                         $purchaseProductDetails = PurchaseProductDetails::find($content["id"]);
                        //abort(406,"product= ".$purchaseProductDetails->product_id." stock= ".$purchaseProductDetails->stock." lote= ".$purchaseProductDetails->plot);
                         $purchaseProductDetails->update([
                            'product_id'    => $product->id,
                            'code'          => strtoupper($content->input('code')),
                            'plot'          => strtoupper($content->input('plot_input')),
                            'quantity'      => $content->input('quantity'),
                            'date_expiration'=> $content->input('date_expiration'),
                            'stock'      => $content->input('quantity'),
                        ]);
                        $purchaseProductDetails->save();
                     }else{
                        //guardar
                        $idsNewsDetailPurchases[]=$purchase->detailPurchases()->create([
                            'product_id'    => $product->id,
                            'code'          => strtoupper($content->input('code')),
                            'plot'          => strtoupper($content->input('plot_input')),
                            'quantity'      => $content->input('quantity'),
                            'date_expiration'=> $content->input('date_expiration'),
                            'stock'      => $content->input('quantity'),
                        ])->id;
                     }
    
                }
            }

            
            $idsViewsPurchases=Array();
            $idsOldsDetailPurchases=Array();
            if($request->has('purchase')){
                $idsViewsPurchases=collect($request->input('purchase'))->where('id','>',0)->pluck('id')->toArray();
            }
            $idsOldsDetailPurchases=$purchase->detailPurchases->pluck('id')->toArray();
                
            $idsDetailPurchases=array_diff($idsOldsDetailPurchases,$idsNewsDetailPurchases);
                
            $idsResultDetailPurchases=array_diff($idsDetailPurchases,$idsViewsPurchases);
            $ids=Arr::flatten($idsResultDetailPurchases);
            foreach($ids as $id){
                $purchase->detailPurchases->find($id)->delete();
            }
            //abort(406,"682 ".json_encode($ids));

            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            return false;
        }

    }

    //VALIDACION DEL GUARDADO DE COMPRAS
    private function validateFormPurchase(Request $request){

        $niceNames = [
            'code_foxcon' => 'Codigo Foxcon',
            'document_type_id' => 'Documento Foxcon',
            'purchase.*.product_id' => 'Producto',
            'purchase.*.code' => 'Codigo',
            'purchase.*.plot_input' => 'Lote',
            'purchase.*.quantity' => 'Cantidad',
        ];
       
          //Validar si la integración con foxcon está activa
          if(\App\Integration::find(1)->enabled && 
in_array(
	\App\Headquarter::find(
		session('headquarter_id')
	)->id , \App\Integration::find(1)->headquarters
)){
            $request->validate([
                'code_foxcon' => 'required',
                'document_type_id' => 'required',
                'purchase.*.product_id' => 'required',
                //'purchase.*.code' => 'required',
                //'purchase.*.plot_input' => 'required',
                'purchase.*.quantity' => 'required|numeric',
            ],
            [], $niceNames);
          }else{
            $request->validate([
                'code_foxcon' => 'required',
                'purchase.*.product_id' => 'required',
                'purchase.*.code' => 'required',
                'purchase.*.plot_input' => 'required',
                'purchase.*.quantity' => 'required|numeric',
            ],
            [], $niceNames);
          }
        
    }

    private function _createNewProduct($string){
        $product = New Product();
        $product->name = $string;
        $product->save();
        return $product;
    }

    public function deleteFormPurchase(Request $request){
        $id        = $request->input('id');
        $purchase  = Purchase::with(['detailPurchases.product'])->find($id);
        $form      = view('inventory.forms.deleteFormShopping', compact('purchase'))->render();

        return response(['success' => true, 'view' => $form]);

    }

    //AGREGAR UN FILA NUEVA DE DETALLES DE COMPRAS
    public function addPurchaseRow(Request $request){
        $key       = uniqid();
        $row       = view('inventory.forms.frames.purchase_new_row',compact(['key']))->render();
        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    //FORMULARIO PARA ELIMINAR UN DETALE DE ORDEN DE COMPRA
    public function deletePurchaseRow(Request $request){
        $id        = $request->input('id');
        $detailPurchase  = PurchaseProductDetails::with(['product'])->find($id);
        $form      = view('inventory.forms.deleteFormPurchaseDetail', compact('detailPurchase'))->render();

        return response(['success' => $detailPurchase? true: false, 'view' => $form]);
    }

    //ELIMINAR EL DETALLE DE LA ORDEN DE COMPRA
    public function softDeleteDetailPurchase(Request $request)
    {
        $id     = $request->input('id');
        $note   = PurchaseProductDetails::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeletedPuchaseDetail : $this->msgError]); 
    }

/******************************************************************************/
    //INVENTARIO - TRANSFERENCIA


    //LISTA DE TRANSFERENCIA
    public function listTransferInventory(Request $request) {
        $warehouse   = Warehouse::find($request->warehouse);
        $inventoryTransfers = TransferInventory::with(['warehouseTransfer','warehouse','detailTranferInventory.product'])
                ->orderBy('id', 'desc')
                ->where('warehouse_id', $request->warehouse)
                ->get();
        $list = view('inventory.sub.listTransferInventory', compact(['inventoryTransfers','warehouse']))->render();
        
        return response(['success' => true, 'view' => $list]);
    }
    //LISTA DE TRANSFERENCIA
    public function listGiftInventory(Request $request) {
        $warehouse   = Warehouse::find($request->warehouse);
        $transferInventory = GiftInventory::orderBy('id', 'desc')
                ->where('warehouse_id', $request->warehouse)
                ->get();
        $list = view('inventory.sub.listGiftInventory', compact(['transferInventory','warehouse']))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    //FORMULARIO DE TRANSFERENCIA
    public function moveInventoryForm(Request $request)
    {
        $warehouse   = Warehouse::find($request->input('warehouse'));
        $id = $request->input('id');
        $transferInventory = TransferInventory::with(['warehouse','detailTranferInventory.product','orderPurchases','warehouseTransfer'])->where('enabled',1)->find($id);
        if($request->filled('id') && !$transferInventory) abort(406,"Por favor, recargue la pagina e intente nuevamente");
        $form = view ('inventory.forms.moveInventory',compact(['warehouse','transferInventory']))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    public function consumptionForm(Request $request)
    {
        $warehouse =  Warehouse::find($request->input('warehouse'));
        $warehouseParent   = Warehouse::with(['configurationFoxcon' => function($q){
            $q->where('document_type_id',3);
        }])->find($warehouse->parent);

        $id = $request->input('id');
        $inventory = Inventory::with(['warehouse'])->where('enabled',1)->find($id);
        if($request->filled('id') && !$inventory) abort(406,"Por favor, recargue la pagina e intente nuevamente");
        $form = view('inventory.forms.consumptionInventory',compact(['warehouse','inventory', 'warehouseParent']))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    public function saveConsumption(Request $request)
    {
        
        $niceNames = [
            'code_foxcon' => 'Codigo Foxcon',
            'document_type_id' => 'Documento Foxcon',
            'purchase.*.product_id' => 'Producto',
            'purchase.*.code' => 'Codigo',
            'purchase.*.plot_input' => 'Lote',
            'purchase.*.quantity' => 'Cantidad',
        ];

        $request->validate([
            'code_foxcon' => 'required',
            'document_type_id' => 'required',
            'transferInventory.*.product_id' => 'required',
            'transferInventory.*.foxcon' => 'required',
            'transferInventory.*.plot' => 'required',
            'transferInventory.*.quantity' => 'required|numeric',
        ],
        [], $niceNames);


        try {
            DB::beginTransaction();
                $inventoryService = new InventoryService;
                $inventoryService->saveReportedExpensePlot($request->warehouse_id, $request->input('transferInventory'), Auth::user(), $request->facturable);
            DB::commit();

            return response(["success" => true, "message" => 'Consumo realizado correctamente']); 

        } catch (\Exception $ex) {
            DB::rollBack();
            return response(["success" => false, "message" => 'Error realizando el consumo: '. $ex->getMessage(), "trace" => $ex->getTrace()]); 
        }
       

    }
      

    //SELECT DE BODEGAS EN EL FORMULARIO DE TRANSFERENCIA
    public function warehouse(Request $request){
        $id = $request->input('warehouse');

        $thisw = Warehouse::find($id);

        if($thisw->warehousable_type == 'App\Cellar'){

            $query = Warehouse::select(['id','parent', 'name as text'])->where(function($w) use($thisw, $id){
                $w->whereHasMorph('warehousable',[User::class, SharedMechasnims::class])
                ->where(function($w2) use($thisw){
                    $w2->where('parent',$thisw->parent);
                    $w2->whereNotIn('id', [$thisw->id]);
                })->orWhere('parent',$id)->orWhere('id', $thisw->parent);
            });

        }else if($thisw->warehousable_type == 'App\User'){

            $query = Warehouse::select(['id','parent', 'name as text'])->where(function($w) use($thisw, $id){
                $w->whereHasMorph('warehousable',[User::class, SharedMechasnims::class])
                ->where(function($w2) use($thisw){
                    $w2->where('parent',$thisw->parent);
                    $w2->whereNotIn('id', [$thisw->id]);
                })->orWhere('parent',$id)->orWhere('id', $thisw->parent);
            });

        }else{

            $query = Warehouse::select(['id','parent', 'name as text'])->where(function($w) use($thisw, $id){
                $w->where('parent',$id)->orWhere('id', $thisw->parent);
            });

        }

        
        
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $warehouse = $query->get();
        return response(['results' => $warehouse]);
    }
    //SELECT DE TIPO  DOCUMENTO EN EL FORMULARIO DE TRANSFERENCIA
    public function typeDocuments(Request $request){
        $id=$request->id;

        if(($request->model === "App\SharedMechasnims" || $request->model === "App\User" || $request->model === "App\Cellar") && $id != 1){
            $warehouse=Warehouse::select(['parent'])->find($id);
            $id=$warehouse->parent;
        }

        $query = ConfigurarionHeadquarterFoxcon::select(['id', 'nomenclature as text', 'consecutive'])->where('document_type_id',$request->documentId)
        ->where('warehouse_id',$id);
        
        if ($request->input('term')) {
            $query->where('nomenclature', 'LIKE', '%' . $request->input('term') . '%');
        }
        $warehouse = $query->get();

        $warehouse->map(function ($item){
            $code = $item->consecutive;
            $incremet =  intVal($code)+1;
            $item->consecutive = sprintf("%06d", $incremet);
            return $item;
        });

        return response(['results' => $warehouse]);
    }

    //SELECT DE ORDEN DE COMPRA EN EL FORMULARIO DE TRANSFERENCIA
    public function orderPurchase(Request $request, $warehouse){
        $query = OrderPurchase::selectRaw('id, CONCAT(number_order_purchase,\' - (Generada el \',DATE_FORMAT(date_order, "%d/%m/%Y"),\')\') as text')->where('warehouse_id',$warehouse);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $orderPurchase = $query->get();
        return response(['results' => $orderPurchase]);
    }

    //FRAGMENTO CON LA INFORMACION DE LA ORDEN DE COMPRA EN EL FORMULARIO DE TRANSFERENCIA
    public function FormlistOrderPurchase(Request $request, $order)
    {
        $key = uniqid();
        $orderPurchase = OrderPurchase::with(['detailOrderPurchases.product.unit'])->find($order);
        $form = view ('inventory.forms.frames.moveForm_pivot',compact(['orderPurchase','key']))->render();
        return response (['success'=>true, 'view' => $form, 'key' => $key,'products'=>$orderPurchase->detailOrderPurchases->pluck('product_id')]);
    }

    //SELECT DE LOTE EN EL FORMULARIO DE TRANSFERENCIA
    public function plot(Request $request, $product){
        $warehouseId = $request->input('warehouse_id');
        $query = InventoryDetail::select(['plot as fake','plot as text'])->where('product_id',$product)->where('warehouse_id',$warehouseId);
        
        if ($request->input('term')) {
            $query->where('plot', 'LIKE', '%' . $request->input('term') . '%');
        }

        if($request->filled('facturable') && $request->input('facturable') == '1'){
            $query->where('facturable', 1);
        }else{
            $query->where('facturable', 0);
        }


        $plots = $query->get();
        return response(['results' => $plots]);
    }
    

    //INPUT DE FOXCON EN EL FORMULARIO DE TRANSFERENCIA
    public function foxcon(Request $request){
        $warehouseId = $request->input('warehouse_id');
        $query = InventoryDetail::select(['code'])->where('plot',$request->plot)->where('warehouse_id',$warehouseId);
        if ($request->input('term')) {
            $query->where('code', 'LIKE', '%' . $request->input('term') . '%');
        }
        $code = $query->get()->first();
        return response($code);
    }

    //INPUT DE FECHA DE EXPIRACION EN EL FORMULARIO DE TRANSFERENCIA
    public function expiration(Request $request){
        $warehouseId = $request->input('warehouse_id');
        $query = InventoryDetail::selectRaw('DATE_FORMAT(date_expiration, \'%d-%m-%Y\') as date_expiration' )->where('plot',$request->plot)->where('warehouse_id',$warehouseId);
        if ($request->input('term')) {
            $query->where('date_expiration', 'LIKE', '%' . $request->input('term') . '%');
        }
        $date = $query->get()->first();
        return response($date);
    }

    //INPUT DE CANTIDAD DOSPONIBLE EN EL FORMULARIO DE TRANSFERENCIA
    public function quantity(Request $request){
        $warehouseId = $request->input('warehouse_id');
        $query = InventoryDetail::select(['stock'])->where('product_id',$request->input('product_id'))->where('plot',$request->plot)->where('warehouse_id',$warehouseId);
        if ($request->input('term')) {
            $query->where('stock', 'LIKE', '%' . $request->input('term') . '%');
        }
        $quantity = $query->get()->first();
        return response($quantity);
    }

     //GUARDAR EL FORMULARIO DE TRANSFERENCIA
    public function storeTransferInventory(Request $request)
    {
        $transferInventory = new TransferInventory;
        $success = $this->saveTranferInventory($request, $transferInventory);

        return response(["success" => $success,"warehouse" => $request->input('warehouse_id'), "message" => $success ? $this->msgSuccessInventory : $this->msgError]);
    }

    public function saveTranferInventory(Request $request, TransferInventory $transferInventory){

        $this->validateFormTransferInventory($request,$transferInventory);

        try {

            DB::beginTransaction();

            if(\App\Integration::find(1)->enabled && 
            in_array(
                \App\Headquarter::find(
                    session('headquarter_id')
                )->id , \App\Integration::find(1)->headquarters
            )){
                if($transferInventory->id == null){
                    $configFoxcon=$this->getDocumentFoxcon($request->document_type_id);
                    $numero=$this->getNumberFoxcon($configFoxcon->consecutive+1);
                    $configFoxcon->consecutive = $numero;
                    $configFoxcon->save();
                }
            }
            
            

            $transferInventory->date_transfer = $request->input('date_transfer');
            $transferInventory->document_type_id = $request->input('document_type_id');
            $transferInventory->headquarter_id = session('headquarter_id');
            $transferInventory->warehouse_id = $request->input('warehouse_id');
            $transferInventory->warehouse_transfer = $request->input('warehouse_transfer');
            $transferInventory->order_id = $request->input('order_id');
            $transferInventory->observation = $request->input('observation');
            $transferInventory->user_id=Auth::user()->id;
            $transferInventory->pre_document_number = $request->code_foxcon;
            $transferInventory->save();
            
            //esta variable se envia a la funcion deductQuantityInventory para localizar la bodega saliente y desminuir 
            $warehouse = $request->input('warehouse_id');
            $warehouse_transfer_type=Warehouse::find($request->input('warehouse_transfer'));
            if(
                $warehouse_transfer_type->warehousable_type === "App\Cellar" || 
                $warehouse_transfer_type->warehousable_type === "App\SharedMechasnims" || 
                $warehouse_transfer_type->warehousable_type === "App\Office"){

                    $this->saveInventaryDirectly($request,$transferInventory);

            }else{
                    
            
                if($request->has('transferInventory')){
                    foreach ($request->input('transferInventory') as $indx => $content) {
                        $content = new Request($content);
                        
                        $collection = collect($request->input('transferInventory'));
                        $sum_value=$collection->where('product_id',$content->input('product_id'))->where('plot',$content->input('plot'))->pluck('transfer_quantity')->sum();
                
                        $productInventorysDeduct = Inventory::where('warehouse_id',$request->input('warehouse_id'))
                        ->where('product_id',$content->input('product_id'))
                        ->get();
                        $product= Product::find($content->product_id);
                        if(count($productInventorysDeduct) === 0) abort(406,"Stock insuficiente del producto $product->name");
                        foreach($productInventorysDeduct as $productInventoryDeduct){
                            $detailsInventori=$productInventoryDeduct->detailInventories->where('plot',$content->input('plot'));
                            foreach ($detailsInventori as $inventorydet){
                                $result=$inventorydet->stock-$sum_value;
                                if($result < 0){
                                    abort('406', "La cantidad a tranferir del producto '".$productInventoryDeduct->product->name."', NO debe ser mayor al Stock del lote '".$inventorydet->plot."'");
                                }
                            }
                        }

                        try {
                            $date_expiration= $content->input('date_expiration') != 'N/A' ? Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00') : 'N/A';
                        } catch (\Exception $ex) {
                            $date_expiration = 'N/A';
                        }
                        
                        
                        //actualizar archivo
                        if($content->has('id')){
                                $transferInventoryDetails = TransferInventoryDetail::find($content["id"]);
                                $content->merge(['old_transfer_quantity' => $transferInventoryDetails->transfer_quantity]);
                                $transferInventoryDetails->update([
                                    'product_id'        => $content->input('product_id'),
                                    'rental'            => $content->input('rental'),
                                    'facturable'        => $content->input('facturable'),
                                    'cost'              => $this->clear_format($content['cost']),
                                    'plot'              => $content->input('plot'),
                                    'foxcon'            => $content->input('foxcon'),
                                    'date_expiration'   => $date_expiration,
                                    'quantity'          => $content->input('quantity'),
                                    'transfer_quantity' => $content->input('transfer_quantity'),
                                ]);
                                //$transferInventoryDetails->save();
                               
                                $this->deductQuantityInventory($content,$warehouse, true);
                        }else{
                                //guardar
                                $transferInventory->detailTranferInventory()->create([
                                'product_id'        => $content->input('product_id'),
                                'rental'            => $content->input('rental'),
                                'facturable'        => $content->input('facturable'),
                                'cost'              => $this->clear_format($content['cost']),
                                'plot'              => $content->input('plot'),
                                'foxcon'            => $content->input('foxcon'),
                                'date_expiration'   => $date_expiration,
                                'quantity'          => $content->input('quantity'),
                                'transfer_quantity' => $content->input('transfer_quantity'),
                            ]);
                            $this->deductQuantityInventory($content,$warehouse, false);
                        }

                        
                    }
                }
            }

                  
            DB::commit();
            return true;
        } catch (\Exception $exc) {
            DB::rollback();
            return false;
        }

    }

    private function validateFormTransferInventory(Request $request) {

        $niceNames = [
            'warehouse_transfer' => 'Bodega a transferir',
            'document_type_id' => 'Tipo documento',
            'transferInventory.*.product_id' => 'Producto',
            'transferInventory.*.transfer_quantity' => 'Cantidad a transferir superada',
            'transferInventory.*.plot' => 'lote'
        ];
       

        //Validar si la integración con foxcon está activa
        if(\App\Integration::find(1)->enabled && 
            in_array(
                \App\Headquarter::find(
                    session('headquarter_id')
                )->id , \App\Integration::find(1)->headquarters
            )){
            $request->validate([
                'warehouse_transfer' => 'required',
                'document_type_id' => 'required',
                'transferInventory.*.product_id' => 'required',
                'transferInventory.*.transfer_quantity' => 'required|numeric|min:1',
                'transferInventory.*.plot' => 'required'
            ],
            [], $niceNames);
        }else{
            $request->validate([
                'warehouse_transfer' => 'required',
                'transferInventory.*.product_id' => 'required',
                'transferInventory.*.transfer_quantity' => 'required|numeric|min:1',
                'transferInventory.*.plot' => 'required'
            ],
            [], $niceNames);
        }
        
        

    }
     //GUARDAR EL FORMULARIO DE TRANSFERENCIA
    public function storeGiftInventory(Request $request)
    {
        $success = $this->saveGiftInventory($request);

        return response(["success" => $success,"warehouse" => $request->input('warehouse_id'), "message" => $success ? "Obsequio realizado correctamente" : $this->msgError]);
    }

    public function saveGiftInventory(Request $request){

        $this->validateFormGiftInventory($request);

        try {
            DB::beginTransaction();
            
            if($request->has('transferInventory')){
                foreach ($request->input('transferInventory') as $indx => $content) {
                    
                    $content = new Request($content);
                    $idd = $content->id;
                    $giftInventory = GiftInventory::find($idd);
                    $detail = optional(optional(optional($giftInventory)->foxconDocument)->details)->first();
                    
                    $productInventorysAddition = Inventory::where('warehouse_id',$request->input('warehouse_id'))
                    ->where('product_id',optional($giftInventory)->product_id)
                    ->get();
                    
                    foreach($productInventorysAddition as $productInventoryAddition){
                        $productInventoryAddition->stock += optional($giftInventory)->quantity ?? 0;
                        $productInventoryAddition->save();

                        $detailsInventoriesA=$productInventoryAddition->detailInventories->where('plot',$content->input('plot'));
                        
                        foreach ($detailsInventoriesA as $inventorydetA){
                            $inventorydetA->stock += optional($giftInventory)->quantity ?? 0;
                            $inventorydetA->save();
                        }
                    }

                    $productInventorysDeduct = Inventory::where('warehouse_id',$request->input('warehouse_id'))
                    ->where('product_id',$content->input('product_id'))
                    ->get();
                    
                    foreach($productInventorysDeduct as $productInventoryDeduct){
                        $decreseValue=$content->input('quantity',0);
                        $productInventoryDeduct->stock -= $decreseValue;
                        $productInventoryDeduct->save();
                        if($productInventoryDeduct->stock < 0){
                            abort('406', "La cantidad a obsequiar del producto '".$productInventoryDeduct->product->name."', NO debe ser mayor al Stock");
                        }
                        $detailsInventori=$productInventoryDeduct->detailInventories->where('plot',$content->input('plot'));
                        foreach ($detailsInventori as $inventorydet){
                            $stock_old=$inventorydet->stock-$decreseValue;
                            $inventorydet->stock=$stock_old;
                            $inventorydet->save();
                            
                            if($inventorydet->stock < 0){
                                abort('406', "La cantidad a obsequiar del producto '".$productInventoryDeduct->product->name."', NO debe ser mayor al Stock del lote '".$inventorydet->plot."'");
                            }
                        }
                    }
                    
                    if(filled($idd)){
                        $sync=optional(optional($giftInventory)->foxconDocument)->sync;
                        if($sync === "1") abort(406,"Operacion fallida, por que el obsequio $indx, ya fue sincronizado en foxcon");
                        if(!isset($sync) || !isset($detail)) abort(406,"Operacion fallida, por que el obsequio no tiene registros en foxcon");
                        $detail->producto                    =   $content->product_id;
                        $detail->cantidad                    =   $content->quantity;
                        $detail->save();
                        
                        $giftInventory->product_id                          =   $content->product_id;
                        $giftInventory->quantity                            =   $content->quantity;
                        $giftInventory->configuracion_document_foxcon_id    =   $request->document_type_id;
                        $giftInventory->plot                                =   $content->plot;
                        $giftInventory->observation                         =   $request->observation;
                        $giftInventory->save();
                    }else{
                        $documentFoxconId = null;
                        
                        //Validar si la integración con foxcon está activa
                        if(\App\Integration::find(1)->enabled && 
                        in_array(
                            \App\Headquarter::find(
                                session('headquarter_id')
                            )->id , \App\Integration::find(1)->headquarters
                        )){
                            $documentFoxconId = $this->saveFoxconDocumentsGift($request,$content);
                        }
                        

                        GiftInventory::Create(
                            [
                                'user_id'                               =>  Auth::user()->id,
                                'warehouse_id'                          =>  $request->warehouse_id,
                                'product_id'                            =>  $content->product_id,
                                'quantity'                              =>  $content->quantity,
                                'plot'                                  =>  $content->plot,
                                'observation'                           =>  $request->observation,
                                'document_foxcon_id'                    =>  $documentFoxconId,
                                'configuracion_document_foxcon_id'      =>  $request->document_type_id
                            ]
                        );
                    }
                }
            }
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            return false;
        }

    }

    private function validateFormGiftInventory(Request $request) {

        $niceNames = [
            'document_type_id' => 'Documento Foxcon',
            'transferInventory.*.product_id' => 'Producto',
            'transferInventory.*.quantity' => 'Cantidad a obsequiar',
            'transferInventory.*.plot' => 'Lote'
        ];
       
         //Validar si la integración con foxcon está activa
         if(\App\Integration::find(1)->enabled && 
in_array(
	\App\Headquarter::find(
		session('headquarter_id')
	)->id , \App\Integration::find(1)->headquarters
)){
            $request->validate([
                'document_type_id' => 'required',
                'transferInventory.*.product_id' => 'required',
                'transferInventory.*.quantity' => 'required|numeric|min:1',
                'transferInventory.*.plot' => 'required'
            ],
            [], $niceNames);
         }else{
            $request->validate([
                'transferInventory.*.product_id' => 'required',
                'transferInventory.*.quantity' => 'required|numeric|min:1',
                'transferInventory.*.plot' => 'required'
            ],
            [], $niceNames);
         }
        

    }

    private function saveFoxconDocumentsGift(Request $request,Request $content){
        $configFoxcon=$this->getDocumentFoxconById($request->document_type_id);
        $numero=$this->getNumberFoxcon($configFoxcon->consecutive+1);
        $foxconDocuments = FoxconDocument::create([
            "documento" => strtoupper($configFoxcon->nomenclature),
            "numero" => $numero,
            "timestamp" => now(),
            "grabacion" => now(),
            "sync" => 0,
            "usuario" => $this->getUserIdFoxcon(),
            "nit" => $this->getNitFoxcon($request->warehouse_id),
        ]);
        ConfigurarionHeadquarterFoxcon::find($configFoxcon->id)->update(["consecutive" => $numero]);
        $configFoxcon->save();
        $foxconDocuments->details()->create([
                'documento'          => strtoupper($configFoxcon->nomenclature),
                'numero'             => $numero,
                'timestamp'          => now(),
                'producto'           => $this->getProductFoxcon($content->product_id),
                'bodega'             => $this->getWarehouseFoxcon($request->warehouse_id),
                'cantidad'           => $content->quantity,
                'nit'                => $this->getNitFoxcon($request->warehouse_id),
                'tipoes'             => "S",
                'plot'               => strtoupper($content->plot)
        ]);
        return optional($foxconDocuments)->id;
    }
    
    //PERMITE AGREGAR LA TRANFERENCIA DIRECTAMENTE EN EL INVENTARIO, SIN NECESIDAD DE COMPROBACION DEL USURIO A RECIBIR LA TRANFERENCIA
    private function saveInventaryDirectly(Request $request,TransferInventory $transferInventory){
        $transferInventory->enabled=0;
        $transferInventory->save();
        if($request->has('transferInventory')){
            foreach ($request->input('transferInventory') as $indx => $content) {
                $content = new Request($content);
                $productInventory=null;
                $num_stock=0;
                $productInventorys = Inventory::where('warehouse_id',$request->input('warehouse_transfer'))
                ->where('product_id',$content->input('product_id'))
                ->get();

                $productInventorysDeduct = Inventory::where('warehouse_id',$request->input('warehouse_id'))
                ->where('product_id',$content->input('product_id'))
                ->get();
                
                foreach($productInventorysDeduct as $productInventoryDeduct){
                    $decreseValue=0;
                    $decreseValue=$content->input('transfer_quantity');
                    $productInventoryDeduct->stock -= $decreseValue;
                    $productInventoryDeduct->save();
                    $detailsInventori=$productInventoryDeduct->detailInventories->where('plot',$content->input('plot'));
                    foreach ($detailsInventori as $inventorydet){
                        $stock_old=$inventorydet->stock-$decreseValue;
                        $inventorydet->stock=$stock_old;
                        $inventorydet->save();
                        if($inventorydet->stock < 0){
                            abort('406', "La cantidad a tranferir del producto '".$productInventoryDeduct->product->name."', NO debe ser mayor al Stock del lote '".$inventorydet->plot."'");
                        }
                    }
                }
                foreach($productInventorys as $productInventory){
                    $productInventory->stock += $content->input('transfer_quantity');
                    $productInventory->save();
                }
                if(filled($productInventory)){
                    $num_stock=optional($productInventory->detailInventories)->where('warehouse_id',$request->input('warehouse_transfer'))->where('product_id',$content->input('product_id'))->where('plot',strtoupper($content->input('plot')))->pluck('stock')->sum();
                }
                if(count($productInventorys) > 0){


                    $productInventory->detailInventory()->updateOrCreate(
                        [ 
                            'warehouse_id'       => $request->input('warehouse_transfer'),
                            'product_id'         => $content->input('product_id'),
                            'plot'               => strtoupper($content->input('plot')),
                        ],
                        [
                            'code'               => strtoupper($content->input('foxcon')),
                            'date_expiration'    => $content->input('date_expiration') != 'N/A' ? Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00') : 'N/A',
                            'stock'              => $num_stock+$content->input('transfer_quantity'),
                        ]);

                  
                }else{

                    $inventory = Inventory::create([
                        'headquarter_id'        => $request->input('headquarter_id'),
                        'warehouse_id'          => $request->input('warehouse_transfer'),
                        'product_id'            => $content->input('product_id'),
                        'stock'                 => $content->input('transfer_quantity')
                    ]);

                    $inventory->detailInventory()->updateOrCreate(
                        [ 
                            'warehouse_id'       => $request->input('warehouse_transfer'),
                            'product_id'         => $content->input('product_id'),
                            'plot'               => strtoupper($content->input('plot')),
                        ],
                        [
                            'code'               => strtoupper($content->input('foxcon')),
                            'date_expiration'    => $content->input('date_expiration') != 'N/A' ? Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00') : 'N/A',
                            'stock'              => $num_stock+$content->input('transfer_quantity'),
                        ]);
                }
                $this->registerTransference($content,$transferInventory);
                
            }
        }
        //Validar si la integración con foxcon está activa
        if(\App\Integration::find(1)->enabled && 
        in_array(
            \App\Headquarter::find(
                session('headquarter_id')
            )->id , \App\Integration::find(1)->headquarters
        )){
            $this->saveFoxconDocumentsTechnical($request);
        }
        
    }

    private function registerTransference(Request $content,TransferInventory $transferInventory){
        
        //actualizar archivo
        if($content->has('id')){
            $transferInventoryDetails = TransferInventoryDetail::find($content["id"]);
            $transferInventoryDetails->update([
                'product_id'        => $content->input('product_id'),
                'rental'            => $content->input('rental'),
                'facturable'        => $content->input('facturable'),
                'cost'              => $this->clear_format($content['cost']),
                'plot'              => $content->input('plot'),
                'foxcon'            => $content->input('foxcon'),
                'date_expiration'   => $content->input('date_expiration') != 'N/A' ? Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00') : 'N/A',
                'quantity'          => $content->input('quantity'),
                'transfer_quantity' => $content->input('transfer_quantity'),
            ]);
        }else{
                //guardar
                $transferInventory->detailTranferInventory()->create([
                'product_id'        => $content->input('product_id'),
                'rental'            => $content->input('rental'),
                'facturable'        => $content->input('facturable'),
                'cost'              => $this->clear_format($content['cost']),
                'plot'              => $content->input('plot'),
                'foxcon'            => $content->input('foxcon'),
                'date_expiration'   => $content->input('date_expiration') != 'N/A' ? Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00') : 'N/A',
                'quantity'          => $content->input('quantity'),
                'transfer_quantity' => $content->input('transfer_quantity'),
            ]);
        }   

    }
    private function getDocumentFoxconWarehouse($id){ 
        $warehouse= Warehouse::find($id);
        $result = optional(optional(optional($warehouse)->configurationFoxcon)->where('document_type_id', 5))->last();
        if(!$result) abort(406,"Para registrar el Obsequio en Foxcon, debe configurar los Obsequios de documentos de foxcon, para la bodega > ".optional($warehouse)->name ?? '');
        return $result;
    }
    private function getDocumentFoxcon( $id){ 
        $result=ConfigurarionHeadquarterFoxcon::find($id);
        if(!$result) abort(406,"Por favor, configure los tipos de documentos de foxcon, para cada sede");
        return $result;
    }
    private function getNitFoxcon( $id){ 
        $idd= $id == 1 ? 2 : $id;
        $warehouse= Warehouse::find($idd);
        $nit=null;
        if($warehouse->warehousable_type == 'App\Headquarter') $nit=$warehouse->warehousable->nit;
        if($warehouse->warehousable_type == 'App\User' || $warehouse->warehousable_type == 'App\Cellar' || $warehouse->warehousable_type == 'App\SharedMechasnims' ||  $warehouse->warehousable_type == 'App\Office') $nit=$warehouse->parentt->warehousable->nit;
        if(!$nit) abort(406,"Para registrar la transacción en Foxcon, es necesario configurar el NIT para la sede $warehouse->name");
        return $nit;
    }
    private function getProductFoxcon($id){ 
        $product= Product::find($id);
        $code = $product->code;
        if(!$code) abort(406,"Por favor, configure el codigo Foxcon para el producto $product->name");
        return $code;
    }
    private function getUserIdFoxcon($id=null){ 
        if(isset($id)){
            $user=User::find($id);
            $headquarter = Headquarter::find(session('headquarter_id'));
            $id_foxcon=$headquarter->foxcon_user_id;
            $name=$headquarter->name;
        }else{
            $headquarter = Headquarter::find(session('headquarter_id'));
            $id_foxcon= $headquarter->foxcon_user_id;
            $name=$headquarter->name;
        }
        if(!$id_foxcon) abort(406,"Por favor, configure el id Usuario foxcon de compras para la sede > ".$name);
        return $id_foxcon;
    }

    private function getUserIdFoxconExit($id=null){ 
        if(isset($id)){
            $user=User::find($id);
            $headquarter = Headquarter::find($user->headquarter->id);
            $id_foxcon=$headquarter->foxcon_user_id;
            $name=$headquarter->name;
        }else{
            $headquarter = Headquarter::find(session('headquarter_id'));
            $id_foxcon= $headquarter->foxcon_user_id;
            $name=$headquarter->name;
        }
        if(!$id_foxcon) abort(406,"Por favor, configure el id Usuario foxcon de compras para la sede > ".$name);
        return $id_foxcon;
    }
    private function getWarehouseFoxcon($id){ 
        $warehouse= Warehouse::find($id);
        $code_foxcon = $warehouse->code_foxcon;
        if(!$code_foxcon) abort(406,"Por favor, configure el codigo foxcon para bodega $warehouse->name");
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
    private function saveFoxconDocumentsTechnical(Request $request){
        $configFoxcon=$this->getDocumentFoxcon($request->document_type_id);
        $numero=$this->getNumberFoxcon($configFoxcon->consecutive+1);
        $foxconDocuments = FoxconDocument::create([
            "documento" => $configFoxcon->nomenclature,
            "numero" => $numero,
            "timestamp" => now(),
            "grabacion" => now(),
            "sync" => 0,
            "usuario" => $this->getUserIdFoxcon(),
            "nit" => $this->getNitFoxcon($request->warehouse_id),
        ]);
        // $configFoxcon->consecutive = $numero;
        // $configFoxcon->save();

        if($request->has('transferInventory')){
            foreach ($request->input('transferInventory') as $indx => $content) {
                $content = new Request($content);
                    $foxconDocuments->details()->create([
                            'documento'          => $configFoxcon->nomenclature,
                            'numero'             => $content->input('code_foxcon'),
                            'timestamp'          => now(),
                            'producto'           => $this->getProductFoxcon($content->product_id),
                            'bodega'             => $this->getWarehouseFoxcon($request->warehouse_id),
                            'cantidad'           => $content->transfer_quantity,
                            'nit'                => $this->getNitFoxcon($request->warehouse_id),
                            'tipoes'             => "S",
                            'plot'               => strtoupper($content->input('plot'))
                            
                    ]);
                    $foxconDocuments->details()->create([
                            'documento'          => $configFoxcon->nomenclature,
                            'numero'             => $content->input('code_foxcon'),
                            'timestamp'          => now(),
                            'producto'           => $this->getProductFoxcon($content->product_id),
                            'bodega'             => $this->getWarehouseFoxcon($request->warehouse_transfer),
                            'cantidad'           => $content->transfer_quantity,
                            'nit'                => $this->getNitFoxcon($request->warehouse_transfer),
                            'tipoes'             => "E",
                            'plot'               => strtoupper($content->input('plot'))
                    ]);
            }
        }
    }
    private function deductQuantityInventory(Request $request, $warehouse, $restoreOldQuantity = false) {


        $oldTransferQuantity = 0;
        if($restoreOldQuantity){
            $oldTransferQuantity = intval($request->input('old_transfer_quantity'));
        }

        $inventoryDetail = InventoryDetail::
        where('product_id',$request->input('product_id'))
        ->whereHas('inventory', function($query) use ($warehouse){
            $query->where('warehouse_id',$warehouse);
        })
        ->where('plot',$request->input('plot'))->get()->last();

        if($inventoryDetail){
            $inventoryDetail->stock -= $request->input('transfer_quantity');
            $inventoryDetail->stock += $oldTransferQuantity;
            $inventoryDetail->save();
        }

        $inventory = Inventory::where('product_id',$request->input('product_id'))
        ->where('warehouse_id',$warehouse)->get()->last();
        $inventory->stock -= $request->input('transfer_quantity');
        $inventory->stock += $oldTransferQuantity;
        $inventory->save();
    
    }

    //FORMULARIO PARA ELIMINAR EL REGISTRO DE TRANSFERENCIA
    public function deleteFormTransferInventory(Request $request){
        $id        = $request->input('id');
        $transferInventory  = TransferInventory::with(['detailTranferInventory.product'])->find($id);
        $form = view('inventory.forms.deleteFormTransferInventory', compact('transferInventory'))->render();
        
        return response(['success' => true, 'view' => $form]);
    }

    //ELIMINAR EL REGISTRO DE TRANSFERENCIA
    public function softDeleteTransferInventory(Request $request)
    {
        $id     = $request->input('id');
        $note   = TransferInventory::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeletedTransferInventory : $this->msgError]); 
    }



    //FORMULARIO PARA ELIMINAR UN DETALE DE TRANSFERENCIA
    public function deleteTransferRow(Request $request){
        $id        = $request->input('id');
        $transfer  = TransferInventoryDetail::with(['product'])->find($id);
        $form      = view('inventory.forms.deleteFormTransferDetail', compact('transfer'))->render();

        return response(['success' => $transfer? true: false, 'view' => $form]);
    }
    //FORMULARIO PARA ELIMINAR UN DETALE DE TRANSFERENCIA
    public function deleteGiftRow(Request $request){
        $id        = $request->input('id');
        $transfer  = GiftInventory::find($id);
        $form      = view('inventory.forms.deleteFormGiftDetail', compact('transfer'))->render();

        return response(['success' => $transfer? true: false, 'view' => $form]);
    }

    //ELIMINAR EL DETALLE DE LA ORDEN DE TRANSFERENCIA
    public function softDeleteDetailTransfer(Request $request)
    {
        $id     = $request->input('id');
        $note   = TransferInventoryDetail::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeletedTransferDetail : $this->msgError]); 
    }
    //ELIMINAR EL DETALLE DE LA ORDEN DE TRANSFERENCIA
    public function softDeleteDetailGift(Request $request)
    {

       try {
        DB::beginTransaction();  

            $date = Carbon::now();
            $date_expiration = $date->addYear()->format('Y-m-d 00:00:00');

            $id              = $request->input('id');
            $giftInventori   = GiftInventory::find($id);

            $giftInventory = optional($giftInventori);
            $foxconDocument = optional($giftInventori->foxconDocument);
            if($foxconDocument->sync === "1") abort(406,"Operacion fallida, por que este Obsequio, ya fue sincronizado en foxcon");
            if(!isset($foxconDocument->sync) || !isset($foxconDocument)) abort(406,"Operacion fallida, por que el obsequio no tiene registros en foxcon");


            $foxconDocument->delete();

            $productInventorys = Inventory::where('warehouse_id',$giftInventory->warehouse_id)
                ->where('product_id',$giftInventory->product_id)
                ->get();

                foreach($productInventorys as $productInventory){
                    $productInventory->stock += $giftInventory->quantity ?? 0;
                    $productInventory->save();
                }
                if(isset($productInventory)){
                    $num_stock=optional($productInventory->detailInventories)->where('warehouse_id',$giftInventory->warehouse_id)->where('product_id',$giftInventory->product_id)->where('plot',$giftInventory->plot)->pluck('stock')->sum();
                    $giftInventory->quantity += $num_stock;

                }
                if(count($productInventorys) > 0){
                    $aa = $productInventory->detailInventory()->updateOrCreate(
                        [ 
                            'warehouse_id'       => $giftInventory->warehouse_id,
                            'product_id'         => $giftInventory->product_id,
                            'plot'               => $giftInventory->plot,
                        ],
                        [
                            'code'               => 'N/A',
                            'date_expiration'    => $date_expiration,
                            'stock'              => $giftInventory->quantity,
                        ]);
                    $aa->save();
                }else{

                    $inventory = Inventory::create([
                        'headquarter_id'        => Auth::user()->headquarter_id,
                        'warehouse_id'          => $giftInventory->warehouse_id,
                        'product_id'            => $giftInventory->product_id,
                        'stock'                 => $giftInventory->quantity,
                    ]);
                    $inventory->detailInventory()->updateOrCreate(
                        [ 
                            'warehouse_id'       => $giftInventory->warehouse_id,
                            'product_id'         => $giftInventory->product_id,
                            'plot'               => $giftInventory->plot,
                        ],
                        [
                            'code'               => 'N/A',
                            'date_expiration'    => $date_expiration,
                            'stock'              => $giftInventory->quantity,
                        ]);
                }
                $note = $giftInventory->delete();

            DB::commit();
                return response(["id" => optional($giftInventory)->id,"success" => 'true', "message" => $note ? "Obsequio eliminado correctamente" : $this->msgError]); 
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            return response(["success" => 'false', "message" =>  $this->msgError]); 
        }
    }
/******************************************************************************/
   //INVENTARIO - ORDENES DE COMPRA

    public function indexOrderPurchase(){        
        return view('inventory.indexOrderPurchase');
    }

   //LISTA DE ORDENES DE COMPRA
    public function listOrderPurchase() {
        $orderPurchases = OrderPurchase::with(['warehouse','detailOrderPurchases.product'])
        ->orderBy('id', 'desc')
        ->OrderPurchase()
        ->where('enabled',1)
        ->get();
        $list = view('inventory.sub.listOrderPurchase',compact(['orderPurchases']))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    //FORMULARIO DE ORDEN DE COMPRAS
    public function formOrderPurchase(Request $request)
    {
        $key       = uniqid();
        $id = $request->input('id');
        $numberOrder = DB::table('order_purchases')->max('number_order_purchase')+1;
        $orderPurchase = OrderPurchase::with(['warehouse','detailOrderPurchases.product'])->find($id);
        $form = view ('inventory.forms.orderPurchaseForm',compact(['orderPurchase','key','numberOrder']))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    //SELECT DE BODEGAS EN EL FORMULARIO DE TRANSFERENCIA
    public function warehouseOrderPurchase(Request $request){
        $query = Warehouse::select(['id','parent', 'name as text'])->where('parent',1);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $warehouseOrder = $query->get();
        return response(['results' => $warehouseOrder]);
    }

    //GUARDAR LA ORDEN DE ORDEN DE COMPRAS
    public function storeOrderPurchase(Request $request) 
    {
        $orderPurchase = new OrderPurchase;
        $success = $this->saveOrderPurchase($request, $orderPurchase);

        return response(["success" => $success, "message" => $success ? $this->msgSuccessOrderPuchase : $this->msgError]);
    }

    //TRANSACION DEL GUARDADO  DE LA ORDEN  DE COMPRAS
    public function saveOrderPurchase(Request $request, OrderPurchase $orderPurchase){
        
        $this->validateOrderPurchases($request);



       try {
           DB::beginTransaction();  
           
           $orderPurchase->headquarter_id = $request->input('headquarter_id');
           $orderPurchase->number_order_purchase = $request->input('number_order_purchase');
           $orderPurchase->date_order = $request->input('date_order');
           $orderPurchase->warehouse_id = $request->input('warehouse_id');
           $orderPurchase->observation = $request->input('observation');
           $orderPurchase->save();
   
           if($request->has('orderPurchase')){
               foreach ($request->input('orderPurchase') as $indx => $content) {
                   $content = new Request($content);

                   

                    //actualizar detalle de la orden de compra
                    if($content->has('id')){
                        $orderPurchaseDetail = OrderPurchaseDetail::find($content["id"]);
                        $orderPurchaseDetail->fill($content->all());
                        $orderPurchaseDetail->save();
                   }else{
                            //guardar los detalles de las ordenes de compra 
                            $product=null;
                           if(!is_numeric($content->input('product_id'))){
                               $product = Product::firstOrCreate(['name' => $content->input('product_id'), 'enabled' => 0]);
                           } else{
                               $product = Product::find($content->input('product_id'));
                           }
                            $orderPurchase->detailOrderPurchases()->create([
                           'product_id'    => $product->id,
                           'quantity'      => $content->input('quantity'),
                       ]);
                   }
               }
           }
           
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            return false;
        }
    }

    //FUNTION QUE VALIDO EL GUARDADO  DE LA ORDEN  DE COMPRAS
    private function validateOrderPurchases(Request $request){
        $niceNames = [
            'warehouse_id' => 'Bodega',
            'orderPurchase.*.quantity' => 'Cantidad',
        ];
    
        
        $request->validate([
            'warehouse_id' => 'required',
            'orderPurchase.*.quantity' => ['required', 'numeric' ,new InventoryLessThanOrEquals($request, 'orderPurchase')]
        ],
        [], $niceNames);
    }

    //ACTUALIZAR LA ORDEN DE ORDEN DE COMPRAS
    public function updateOrderPurchase(Request $request)
    {
        $id        = $request->input('id');
        $orderPurchase  = OrderPurchase::find($id);
        $success   = $this->saveOrderPurchase($request, $orderPurchase);

        return response(["success" => $success, "message" => $success ? $this->updateOrderPuchase : $this->msgError]);
    }

    //FORMULARIO PARA ELIMINAR LA DE ORDEN DE COMPRA
    public function deleteFormOrderPurchase(Request $request){
        $id        = $request->input('id');
        $orderPurchase  = OrderPurchase::find($id);
        $form      = view('inventory.forms.deleteFormOrderPurchase', compact('orderPurchase'))->render();
        
        return response(['success' => true, 'view' => $form]);
    }

    //ELIMINAR LA ORDEN DE COMPRA
    public function softDeleteOrderPurchase(Request $request)
    {
        $id     = $request->input('id');
        $note   = OrderPurchase::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeletedOrderPuchase : $this->msgError]); 
    }

    //AGREGAR UN FILA NUEVA DE DETALLES DE ORDEN DE COMPRA
    public function addRowOrderPurchase(Request $request){
        $key       = uniqid();
        $row       = view('inventory.forms.frames.orderPurchase_new_row',compact(['key']))->render();
        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    //FORMULARIO PARA ELIMINAR UN REGISTRO DE ORDEN DE COMPRA
    public function deleteOrderPurchaseRow(Request $request){
        $id        = $request->input('id');
        $orderPurchase  = OrderPurchaseDetail::with(['product'])->find($id);
        $form      = view('inventory.forms.deleteFormDetailOrderPurchase', compact('orderPurchase'))->render();

        return response(['success' => $orderPurchase? true: false, 'view' => $form]);
    }

    //ELIMINAR EL DETALLE DE LA ORDEN DE COMPRA
    public function softDeleteDetailOrderPurchase(Request $request)
    {
        $id     = $request->input('id');
        $note   = OrderPurchaseDetail::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeletedOrderPuchaseDetail : $this->msgError]); 
    }

/******************************************************************************/
    //INVENTARIO - CONFIGURACION

    //LISTA DE CONFIGURACION
    public function listSettings(Request $request) {
        $warehouse   = Warehouse::find($request->warehouse);
        $settingProduct = ProductWarehouseConfig::with(['product','warehouse'])
                ->orderBy('id', 'desc')
                ->where('warehouse_id', $request->warehouse)
                ->get();
        $list = view('inventory.sub.listSettings', compact('settingProduct','warehouse'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    //FORMULARIO DE CONFIGURACION DE STOCK
    public function settingInventoryForm(Request $request)
    {
        $key       = uniqid();
        $warehouse   = Warehouse::find($request->input('warehouse'));
        $id = $request->input('id');
        $settingProductWarehouse = ProductWarehouseConfig::with(['product'])->find($id);
        $form = view ('inventory.forms.settingsInventory',compact(['settingProductWarehouse','warehouse','key']))->render();
        return response (['success'=>true, 'view' => $form, 'key' => $key]);
    }
    //FORMULARIO DE CONFIGURACION DE FOXCON
    public function settingInventoryFoxconForm(Request $request)
    {
        $key                          = uniqid();
        $warehouse                  = Warehouse::with('configurationFoxcon')->find($request->input('warehouse'));
        $form = view ('inventory.forms.settingsFoxconInventory',compact(['warehouse','key']))->render();
        return response (['success'=>true, 'view' => $form, 'key' => $key]);
    }

    //AGREGAR UNA FILA NUEVA EL FORMULARIO DE CONFIGURACION DE STOCK
    public function addRowSetting(Request $request){
        $key       = uniqid();
        $row       = view('inventory.forms.frames.settings_new_row',compact(['key']))->render();
        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }
    //AGREGAR UNA FILA NUEVA EL FORMULARIO DE CONFIGURACION DE STOCK
    public function addRowFoxconSetting(Request $request){
        $key       = uniqid();
        $row       = view('inventory.forms.frames.settingsFoxcon_new_row',compact(['key']))->render();
        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }
    //ELIMINAR UNA FILA DEL FORMULARIO DE CONFIGURACION DE FOXCON
    public function deleteFoxconSetting(Request $request){
        $settingFoxconDelete  = ConfigurarionHeadquarterFoxcon::find($request->id);
        $form      = view('inventory.forms.deleteFormFoxconSetting', compact('settingFoxconDelete'))->render();

        return response(['success' => $settingFoxconDelete ? true : false, 'view' => $form]);

    }

    //ELIMINAR UNA FILA DEL FORMULARIO DE CONFIGURACION DE STOCK
    public function deleteSetting(Request $request){
        $id        = $request->input('id');
        $settingProduct  = ProductWarehouseConfig::with(['product'])->find($id);
        $form      = view('inventory.forms.deleteFormSetting', compact('settingProduct'))->render();

        return response(['success' => $settingProduct? true: false, 'view' => $form]);

    }

    //GUARDAR EL FORMULARIO DE CONFIGURACION DE STOCK
    public function storeProductWarehouse(Request $request)
    {

        $settingProduct = new ProductWarehouseConfig;
        $success = $this->saveProductWarehouse($request, $settingProduct);

        return response(["success" => $success,"warehouse" => $request->input('warehouse_id'), "message" => $success ? $this->msgSuccessProductWarehouse : $this->msgError]);
    }
   

    public function saveProductWarehouse(Request $request, $settingProduct){
        
        $this->validateFormProductWarehouse($request,$settingProduct);
        
        try {
            DB::beginTransaction();
            

            if($request->has('setting')){
                foreach ($request->input('setting') as $indx => $content) {
                    $content = new Request($content);

                    ProductWarehouseConfig::updateOrCreate(
                    [
                        'id'=> $content['id']
                        ],
                    [
                        'warehouse_id'=> $request->input('warehouse_id'),
                        'product_id'=> $content['product_id'],
                        'minimum'=>$content['minimum'], 
                        'maximum'=>$content['maximum'], 
                        'enabled'=>$request->input('enabled')
                        ] 
                    );
                }
            }
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            return false;
        }

    }


    private function validateFormProductWarehouse(Request $request) {

        $niceNames = [
            
            'setting.*.product_id' => 'Nombre del producto',
        ];
       
        
        $request->validate([
            'setting.*.product_id' => 'required',
        ],
        [], $niceNames);

    }

    public function updateFoxconSetting(Request $request)
    {
        $success   = $this->saveFoxconSetting($request);
        return response(["success" => $success,"warehouse" => $request->input('warehouse_id'), "message" => $success ? "Configuracion de Foxcon editada correctamente" : $this->msgError]);
    }
    //GUARDAR EL FORMULARIO DE CONFIGURACION DE FOXCON
    public function storeFoxconSetting(Request $request)
    {
        $success = $this->saveFoxconSetting($request);

        return response(["success" => $success,"warehouse" => $request->warehouse_id, "message" => $success ? "Configuracion de Foxcon guardada correctamente" : $this->msgError]);
    }
   

    public function saveFoxconSetting(Request $request){
        
        $this->validateFormFoxconSetting($request);
        
        try {
            DB::beginTransaction();
            

            if($request->has('settingFoxcon')){
                foreach ($request->input('settingFoxcon') as $indx => $content) {
                    $content = new Request($content);

                    ConfigurarionHeadquarterFoxcon::updateOrCreate(
                    [
                        'id'=> $content['id']
                    ],
                    [
                        'warehouse_id'=> $request->warehouse_id,
                        'nomenclature'=>$content['nomenclature'], 
                        'document_type_id'=>$content['document_type'], 
                        'consecutive'=>$content['consecutive'], 
                        ] 
                    );
                }
            }
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            return false;
        }

    }


    private function validateFormFoxconSetting(Request $request) {
        $settingFoxcon= $request->settingFoxcon;
        foreach ($settingFoxcon as $index => $settingFoxco){
            $id=$settingFoxco['id'] ?? '';
            $nomenclature=$settingFoxco['nomenclature'] ?? '';
            if(isset($id)){
                $nomenclatureCount = ConfigurarionHeadquarterFoxcon::where('nomenclature',$nomenclature)->where('id','!=',$id)->count();
            }else{
                $nomenclatureCount = ConfigurarionHeadquarterFoxcon::where('nomenclature',$nomenclature)->count();
            }
            if($nomenclatureCount > 0) abort (406, "La nomenclature $nomenclature, ya esta registrada");
            
        }
        $niceNames = [
            
            'settingFoxcon.*.document_type' => 'Tipo de documento',
            'settingFoxcon.*.nomenclature' => 'Nomenclatura',
            'settingFoxcon.*.consecutive' => 'Consecutivo',
        ];
       
        
        $request->validate([
            'settingFoxcon.*.consecutive' => 'required|numeric',
            'settingFoxcon.*.document_type' => 'required',
            'settingFoxcon.*.nomenclature' => 'required|alpha_num|size:3',
        ],
        [], $niceNames);

    }

 public function softDeleteFoxconSettings(Request $request)
    {
        $success   = ConfigurarionHeadquarterFoxcon::destroy($request->id);
        return response(["success" => 'true', "message" => $success ? "Configuracion foxcon eliminada correctamente" : $this->msgError]); 
    }

    

/******************************************************************************/

          //INVENTARIO - ORDEN DE COMPRA GENERAL

    //LISTA DE ORDENES DE COMPRA GENERAL
    public function listGeneralOrderPurchase() {
        $generalOrderPurchases = GeneralOrderPurchase::with('detailGeneralOrderPurchases.product')
        ->orderBy('id', 'desc')
        ->get();
        $list = view('inventory.sub.listGeneralOrderPurchase',compact('generalOrderPurchases'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    //FORMULARIO PARA UNIFIRCAR LAS ORDENES DE COMPRA
    public function formGeneralOrderPurchase(Request $request)
    {
        
        $ids = $request->input('ids');
        $numberOrder = DB::table('general_order_purchases')->max('number_order_purchase')+1;
        $orderPurchases = OrderPurchase::with(['detailOrderPurchases.product'])->find($ids);
        if(is_null($ids)){

            return response(["successError" => false, "message" => 'No se pudo unificar las ordenes de compra debido a que no selecciono una orden de compra en la lista']);
        }
        //sumar duplicados
        $detailorders = $orderPurchases->pluck('detailOrderPurchases')->flatten()->groupBy('product_id')->flatMap(function ($items) {
            $quantity = $items->sum('quantity');
            return $items->map(function ($item) use ($quantity) {$item->quantity = $quantity;return $item;});
        })->unique('product_id');

  
        $key       = uniqid();
        $form = view ('inventory.forms.generalOrderPurchaseForm',compact(['key','orderPurchases','detailorders','numberOrder']))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    //GUARDAR EL FORMUMARIO DE ORDEN DE COMPRA GENERAL
    public function storeGeneralOrderPurchase(Request $request) 
    {
        $generalOrderPurchase = new GeneralOrderPurchase;
        $success = $this->saveGeneralOrderPurchase($request, $generalOrderPurchase);

        return response(["success" => $success, "message" => $success ? $this->msgSuccessGeneralOrderPuchase : $this->msgError]);
    }

    //TRANSACION DEL GUARDADO  DE LA ORDEN  DE COMPRAS
    public function saveGeneralOrderPurchase(Request $request, GeneralOrderPurchase $generalOrderPurchase){


         $this->validateGeneralOrderPurchases($request);

       try {
           DB::beginTransaction();  
           
           $generalOrderPurchase->headquarter_id = $request->input('headquarter_id');
           $generalOrderPurchase->number_order_purchase = $request->input('number_order_purchase');
           $generalOrderPurchase->date_order = $request->input('date_order');
           $generalOrderPurchase->observation = $request->input('observation');
           $generalOrderPurchase->save();
   
           if($request->has('orderPurchase')){
               foreach ($request->input('orderPurchase') as $indx => $content) {
                   $content = new Request($content);

         
                    //actualizar detalle de la orden de compra general
                    if($content->has('id')){
                             //guardar los detalles de las ordenes de compra general

                             $product=null;
                             if(!is_numeric($content->input('product_id'))){
                                 $product = Product::firstOrCreate(['name' => $content->input('product_id'), 'enabled' => 0]);
                             } else{
                                 $product = Product::find($content->input('product_id'));
                             }
                         
                             $generalOrderPurchase->detailGeneralOrderPurchases()->create([
                             'product_id'    => $product->id,
                             'quantity'      => $content->input('quantity'),
                         ]);
                    }
   
               }
           }

            if($request->has('order')){
                foreach ($request->input('order') as $indx => $content) {
                    $content = new Request($content);
                        
                    $id = $content->input('orderPurchase_id');
                    $orderPurchases = OrderPurchase::find($id);
                    $orderPurchases->enabled = 0;
                    $orderPurchases->save();


                }
            }

                 
           DB::commit();
           return true;
       } catch (Exception $exc) {
           DB::rollback();
           return false;
       }

    }

    //FUNTION QUE VALIDO EL GUARDADO  DE LA ORDEN  DE COMPRAS
    private function validateGeneralOrderPurchases(Request $request){
        $niceNames = [
            'orderPurchase.*.quantity' => 'Cantidad',
        ];
    
        
        $request->validate([
            'orderPurchase.*.quantity' => ['required', 'numeric' ,new InventoryLessThanOrEquals($request, 'orderPurchase')]
        ],
        [], $niceNames);
    }

    //FORMULARIO PARA EDITAR LAS ORDNES DE COMPRAS GENERAL
    public function EditformGeneralOrderPurchase(Request $request)
    {
        $id = $request->input('id');
        $numberOrder = DB::table('general_order_purchases')->max('number_order_purchase')+1;
        $generalOrderPurchase = GeneralOrderPurchase::with(['detailGeneralOrderPurchases.product'])->find($id);
        $detailorders = $generalOrderPurchase->detailGeneralOrderPurchases;
        $key       = uniqid();
        $form = view ('inventory.forms.generalOrderPurchaseForm',compact(['key','numberOrder','generalOrderPurchase','detailorders']))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    //FORMULARIO PARA ELIMINAR LA ORDEN DE COMPRA GENERAL
    public function deleteFormGeneralOrderPurchase(Request $request){
        $id        = $request->input('id');
        $generalOrderPurchase  = GeneralOrderPurchase::find($id);
        $form      = view('inventory.forms.deleteFormGeneralOrderPurchase', compact('generalOrderPurchase'))->render();
        
        return response(['success' => true, 'view' => $form]);

    }

    //ELIMINAR LA ORDEN DE COMPRA GENERAL
    public function softDeleteGeneralOrderPurchase(Request $request)
    {
        $id     = $request->input('id');
        $note   = GeneralOrderPurchase::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeletedGeneralOrderPuchase : $this->msgError]); 
    }


/******************************************************************************/

          //INVENTARIO - CONFIRMAR INVENTARIO

    //LISTA DE CONFIRMACION DE TRANSFERENCIA
    public function listConTransferInventory() {

       
        $id= [];

        
        if(
            Auth::user()->roles->pluck('slug')->contains('purchasing') ||
            Auth::user()->roles->pluck('slug')->contains('boss_purchasing') ||
            Auth::user()->roles->pluck('slug')->contains('admin') ||
            Auth::user()->roles->pluck('slug')->contains('root')
        ){
            $headquarter = Headquarter::with(['warehouse'])->find(session('headquarter_id'));
            $id[] = $headquarter->warehouse->id;
           
        }

        

        if(Auth::user()->roles->pluck('slug')->contains('technical')){
            $id[] = Auth::user()->warehouse->id;
        }
        
        $confirmationTransfers = TransferInventory::with(['warehouse','detailTranferInventory.product'])
        ->orderBy('id', 'desc')
        ->whereIn('warehouse_transfer',$id)
        ->where('enabled',1)
        ->get();
        $list = view('inventory.sub.listConfirmationTransferInventory',compact('confirmationTransfers'))->render();
        return response(['success' => true, 'view' => $list]);
    }

    //FORMULARIO DE CONFIRMACION DE TRANSFERENCIA
    public function confirmationTransferForm(Request $request)
    {
        $id = $request->input('id');
        $transferInventory = TransferInventory::with(['warehouse','warehouseTransfer','detailTranferInventory.product'])->find($id);

        //$confirmationTransfer = ConfirmationTransferInventory::with(['detailConfirmationTranferInventory'])->find($id);
        $form = view ('inventory.forms.confirmationTranferInventoryForm',compact('transferInventory'))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    //GUARDAR EL FORMULARIO DE CONFIRMACION DE TRANSFERENCIA ////
    public function storeConfirmationTransferInventory(Request $request) 
    {
        $confirmationTransfer = new ConfirmationTransferInventory;
        $inventory = new Inventory;
        $success = $this->saveConfirmationTransferInventory($request, $confirmationTransfer, $inventory);

        return response(["success" => $success, "message" => $success ? $this->msgSuccessConfirmationTransfer : $this->msgError]);
    }
    public function saveConfirmationTransferInventory(Request $request, ConfirmationTransferInventory $confirmationTransfer, Inventory $inventory){
        
        //$this->validateFormTransferInventory($request,$transferInventory);

        try {
            DB::beginTransaction();

          
 
            $confirmationTransfer->headquarter_id = session('headquarter_id');
            $confirmationTransfer->date_transfer = $request->input('data_transfer');
            $confirmationTransfer->warehouse_exit = $request->input('warehouse_exit');
            $confirmationTransfer->warehouse_arrival = $request->input('warehouse_arrival');
            $confirmationTransfer->date_confirmation = Carbon::parse($request->input('date_confirmation'))->format('Y-m-d 00:00:00');
            $confirmationTransfer->observation = $request->input('observation');
            $confirmationTransfer->save();
            
            $cont=0;
            
            if($request->has('confirmationTransfer')){
                foreach ($request->input('confirmationTransfer') as $indx => $content) {
                    $content = new Request($content);
                     //actualizar archivo
                     if($content->has('id')){
                            $confirmationTransferDetails = DetailConfirmationTransfer::find($content["id"]);
                            $confirmationTransferDetails->fill($content->all());
                            $confirmationTransferDetails->save();
                     }else{
                             //guardar
                            $confirmationTransfer->detailConfirmationTranferInventory()->create([
                            'product_id'    => $content->input('product_id'),
                            'foxcon'          => $content->input('foxcon'),
                            'plot'          => $content->input('plot'),
                            'quantity'          => $content->input('transfer_quantity'),
                            'date_expiration' => $content->input('date_expiration') != 'N/A' ? Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00') : 'N/A',
                            'approved'      => 1,
                            'quantity_received' => $content->input('quantity_received'),
                        ]);
                        if($cont === 0){
                            //Validar si la integración con foxcon está activa
                            if(\App\Integration::find(1)->enabled && 
                                (    in_array(
                                        \App\Headquarter::find(
                                            session('headquarter_id')
                                        )->id , \App\Integration::find(1)->headquarters )
                                ) || (

                                    $request->warehouse_exit == 1 && 
                                    \App\Integration::find(1)->enabled && 
                                    in_array('99999999', \App\Integration::find(1)->headquarters)

                                )
                                
                                )

                                    {
                                        $this->saveFoxconDocumentsWarehouse($request);
                                    }

                            $cont=1;
                        }
                    }
                }
            }

            $transferInventory = TransferInventory::find($request->input('transferInventory'));
            $transferInventory->enabled = 0;
            $transferInventory->save();

            if($request->has('confirmationTransfer')){
                foreach ($request->input('confirmationTransfer') as $indx => $content) {
                    $content = new Request($content);
                    $productInventory=null;
                    $num_stock=0;

                        $productInventorys = Inventory::where('warehouse_id',$request->input('warehouse_arrival'))
                        ->where('product_id',$content->input('product_id'))
                        ->get();
                        

                        //TODO ESTO SE COMENTA PARA QUE NO SE DESCUENTE EL STOCK DE LA BODEGA DE SALIDA EN LA CONFIRMACION SINO AL CREAR LA TRANSFERENCIA
                        // $productInventorysDeduct = Inventory::where('warehouse_id',$request->input('warehouse_exit'))
                        // ->where('product_id',$content->input('product_id'))
                        // ->get();
                        

                        // $product=Product::find($content->product_id);
                        //if(count($productInventorysDeduct) === 0) abort(406,"Stock insuficiente del producto $product->name");
                        /*foreach($productInventorysDeduct as $productInventoryDeduct){
                            $decreseValue=0;
                            $decreseValue= intval($content->input('quantity_received'));
                            $productInventoryDeduct->stock -= $decreseValue;
                            $productInventoryDeduct->save();
                            $detailsInventori=$productInventoryDeduct->detailInventories->where('plot',$content->input('plot'));
                            foreach ($detailsInventori as $inventorydet){
                                $stock_old=$inventorydet->stock-$decreseValue;
                                $inventorydet->stock=$stock_old;
                                $inventorydet->save();
                                if($inventorydet->stock < 0){
                                    abort('406', "La cantidad a tranferir del producto '".$productInventoryDeduct->product->name."', NO debe ser mayor al Stock del lote '".$inventorydet->plot."'");
                                }
                            }
                            
                            if($productInventoryDeduct->stock  < 1){ 
                                $productInventoryDeduct->delete();
                                $detailsInventori->each->delete();
                            }
                        }*/

                        foreach($productInventorys as $productInventory){
                            $productInventory->stock += $content->input('quantity_received');
                            $productInventory->save();
                        }
                        
                        if(filled($productInventory)){
                            $num_stock=optional($productInventory->detailInventories)->where('warehouse_id',$request->input('warehouse_arrival'))->where('product_id',$content->input('product_id'))->where('plot',strtoupper($content->input('plot')))->pluck('stock')->sum();
                        }
                        if(count($productInventorys) > 0){


                            $productInventory->detailInventory()->updateOrCreate(
                                [ 
                                    'warehouse_id'       => $request->input('warehouse_arrival'),
                                    'product_id'         => $content->input('product_id'),
                                    'plot'               => strtoupper($content->input('plot')),
                                ],
                                [
                                    'code'               => strtoupper($content->input('foxcon')),
                                    'date_expiration'    => $content->input('date_expiration') != 'N/A' ? Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00') : 'N/A',
                                    'stock'              => $num_stock+$content->input('quantity_received'),
                                    'facturable'         => $content->input('facturable')
                                ]);

                        
                        }else{

                            $inventory = Inventory::create([
                                'headquarter_id'        => $request->input('headquarter_id'),
                                'warehouse_id'          => $request->input('warehouse_arrival'),
                                'product_id'            => $content->input('product_id'),
                                'stock'                 => $content->input('quantity_received')
                            ]);

                            $inventory->detailInventory()->updateOrCreate(
                                [ 
                                    'warehouse_id'       => $request->input('warehouse_arrival'),
                                    'product_id'         => $content->input('product_id'),
                                    'plot'               => strtoupper($content->input('plot')),
                                ],
                                [
                                    'code'               => strtoupper($content->input('foxcon')),
                                    'date_expiration'    => $content->input('date_expiration') != 'N/A' ? Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00') : 'N/A',
                                    'stock'              => $num_stock+$content->input('quantity_received'),
                                    'facturable'         => $content->input('facturable')
                                ]);

    
                        }
                    
                }
            }

            
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            return false;
        }

    }

    //PERMITE GUARDAR LA TRANSFERENCIA EN LA TABLA DE FOXCON CUANDO ES CONFIRMADA LA TRANFERENCIA
    private function saveFoxconDocumentsWarehouse(Request $request){
        $configFoxcon=$this->getDocumentFoxcon($request->type_document_id);
        $numero=$this->getNumberFoxcon($configFoxcon->consecutive+1);
        $foxconDocuments = FoxconDocument::create([
            "documento" => $configFoxcon->nomenclature,
            "numero" => $numero,
            "timestamp" => now(),
            "grabacion" => now(),
            "sync" => 0,
            "nit" => $this->getNitFoxcon($request->warehouse_exit),
            "usuario" => $this->getUserIdFoxconExit($request->user_id),
        ]);
        $configFoxcon->consecutive = $numero;
        $configFoxcon->save();

        if($request->has('confirmationTransfer')){
            foreach ($request->input('confirmationTransfer') as $indx => $content) {
                $content = new Request($content);
                    $foxconDocuments->details()->create([
                            'documento'          => $configFoxcon->nomenclature,
                            'numero'             => $numero,
                            'timestamp'          => now(),
                            'producto'           => $this->getProductFoxcon($content->product_id),
                            'bodega'             => $this->getWarehouseFoxcon($request->warehouse_exit),
                            'cantidad'           => $content->transfer_quantity,
                            'nit'                => $this->getNitFoxcon($request->warehouse_exit),
                            'tipoes'             => "S",
                            'plot'               => strtoupper($content->input('plot'))
                    ]);
                    $foxconDocuments->details()->create([
                            'documento'          => $configFoxcon->nomenclature,
                            'numero'             => $numero,
                            'timestamp'          => now(),
                            'producto'           => $this->getProductFoxcon($content->product_id),
                            'bodega'             => $this->getWarehouseFoxcon($request->warehouse_arrival),
                            'cantidad'           => $content->transfer_quantity,
                            'nit'                => $this->getNitFoxcon($request->warehouse_arrival),
                            'tipoes'             => "E",
                            'plot'               => strtoupper($content->input('plot'))
                    ]);
            }

            //asociar el documento con el registro de transferencia original
            TransferInventory::find($request->input('transferInventory'))->update(['document_foxcon_id' => $foxconDocuments->id]);
        }
    }
    //AGREGAR UN FILA NUEVA DE CONFIRMACION DE TRANSFERENCIA
    public function addRowConfirmationTransfer(Request $request){
        $key       = uniqid();
        $row       = view('inventory.forms.frames.confirmationTransfer_new_row',compact(['key']))->render();
        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    //FORMULARIO PARA ELIMINAR UN REGISTRO DE CONFIRMACION DE TRANSFERENCIA
    public function deleteConfirmationTransferRow(Request $request){
        $id        = $request->input('id');
        $confirmationTransfer  = DetailConfirmationTransfer::find($id);
        $form      = view('inventory.forms.deleteFormDetailConfirmationTransfer', compact('confirmationTransfer'))->render();

        return response(['success' => $confirmationTransfer? true: false, 'view' => $form]);
    }

    //FORMULARIO PARA ELIMINAR REGISTRO DE CONFIRMACION DE TRANSFERENCIA
    public function deleteFormConfirmationTransferInventory(Request $request)
    {
        $id        = $request->input('id');
        $confirmationTransfers  = TransferInventory::with(['detailTranferInventory.product'])->find($id);
        $form      = view('inventory.forms.deleteFormConfirmationTransfer', compact('confirmationTransfers'))->render();
        
        return response(['success' => true, 'view' => $form]);

    }

    //AGREGAR EL REGISTRO DE CONFIRMACION DE TRANSFERENCIA
     public function softDeleteConfirmationTransferInventory(Request $request)
     {
        
 

         $inventoryDetail = InventoryDetail::
         where('product_id',$request->input('product_id'))
         ->where('plot',$request->input('plot'))->get()->last();
        

         if($inventoryDetail){
             $inventoryDetail->stock += $request->input('transfer_quantity');
            $inventoryDetail->save();
        }


        $inventory = Inventory::where('product_id',$request->input('product_id'))
        ->where('warehouse_id',$request->input('warehouse_id'))
        ->where('headquarter_id',$request->input('headquarter_id'))
        ->get()->last();

        if($inventory){
            $inventory->stock += $request->input('transfer_quantity');
           $inventory->save();
       }

    

         $id     = $request->input('id');
         $note   = TransferInventory::destroy($id);
         return response(["success" => 'true', "message" => $note ? $this->msgDeletedConfirmationTransfer : $this->msgError]); 
     }


     public function addRowGiftInventory(Request $request){
        $key       = uniqid();
        $disabled  = null;
        $row       = view('inventory.forms.frames.giftForm_new_row',compact(['key','disabled']))->render();
        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }




    public function addRowTransferInventory(Request $request){
        $key       = uniqid();
        $row       = view('inventory.forms.frames.moveForm_new_row',compact(['key']))->render();
        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    public function addRowConsumptionInventory(Request $request){
        $key       = uniqid();
        $row       = view('inventory.forms.frames.consumptionForm_new_row',compact(['key']))->render();
        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }
    
    public function storeHeadquarter(Request $request){
        $headquarter = new Headquarter();
        $success = $this->saveHeadquarter($request, $headquarter);
        return response(["success" => $success, "message" => $success ? $this->msgHeadquarter : $this->msgError]);
    }











    
    public function updateHeadquarter(Request $request){
        $headquarter = Headquarter::find($request->input('id'));
        $success = $this->saveHeadquarter($request, $headquarter);
        return response(["success" => $success, "message" => $success ? $this->updateHeadquarter : $this->msgError]);
    }
    
    public function updateProductWarehouse(Request $request)
    {
        $id        = $request->input('id');
        $settingProduct  = ProductWarehouseConfig::find($id);
        $success   = $this->saveProductWarehouse($request, $settingProduct);

        return response(["success" => $success,"warehouse" => $request->input('warehouse_id'), "message" => $success ? $this->updateProductWarehouse : $this->msgError]);
    }

    public function updateTransferInventory(Request $request)
    {
        $id        = $request->input('id');
        $transferInventory  = TransferInventory::where('enabled',1)->find($id);
        if(blank($transferInventory)) abort(406,"Por favor recargue la pagina, e intente nuevamente ");
        $success   = $this->saveTranferInventory($request, $transferInventory);

        return response(["success" => $success,"warehouse" => $request->input('warehouse_id'), "message" => $success ? $this->updateTransferInventory : $this->msgError]);
    }


            
    public function saveHeadquarter(Request $request, Headquarter $headquarter){
        $this->validateForm($request,'headquarter');
        $headquarter->name      = $request->input('name');
        $headquarter->city_id   = $request->input('city_id');
        $headquarter->address   = $request->input('address');
        $headquarter->phone     = $request->input('phone');
        
         try {
            DB::beginTransaction();
            
                $headquarter->save();
                $headquarter->zones()->sync($request->input('zones'));
                //$this->createZones($request, $headquarter);
                $this->createOrupdateHeadquarterWarehouse($request, $headquarter);
                
            DB::commit();
                return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();
            return false;
        } 
    }






    
    private function createZones(Request $request, $headquarter){
        foreach ($request->input('zones') as $indx => $zone) {
            $headquarter->zones()->create([
                "name" =>  $zone
            ]);
        }
    }
    
    private function createOrupdateHeadquarterWarehouse(Request $request, $headquarter){
        if(is_null($request->input('id'))){
           $headquarter->warehouse()->create([
                    "name" => $headquarter->name,
                    "parent" => $request->input('parent'),
                    "buttons" => ['add' => true, 'buy'=> true, 'conf'=> true, 'delete' =>true, 'edit'=> true, 'transf' => true],
                    "icon" => 'headquarter',
                    "action" => true
                ]); 
        }else{
            $headquarter->warehouse()->update([
                    "name" => $headquarter->name
                ]); 
        }
        
    }
    
    public function storeCellar(Request $request){
        $cellar = new Cellar();
        $success = $this->saveCellar($request, $cellar, 'Locativa '. $request->input('name'));
        return response(["success" => $success, "message" => $success ? $this->msgHeadquarter : $this->msgError]);
    }
    
    public function updateCellar(Request $request){
        $cellar = Cellar::find($request->input('id'));
        $success = $this->saveCellar($request, $cellar, $request->input('name'));
        return response(["success" => $success, "message" => $success ? $this->updateHeadquarter : $this->msgError]);
    }
    
    public function saveCellar(Request $request, Cellar $cellar, $name){
        $this->validateForm($request,'cellar');
        $cellar->name      = $name;
        $cellar->address   = $request->input('address');
        $cellar->phone      = $request->input('phone');
        $cellar->headquarter_id = session('headquarter_id');
        
         try {
            DB::beginTransaction();
                $cellar->save();
                $this->createOrUpdateCellarWarehouse($request, $cellar);
            DB::commit();
                return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();
                return false;
        }
    }
    
    private function createOrUpdateCellarWarehouse(Request $request, $cellar){
        if(is_null($request->input('id'))){
           $cellar->warehouse()->create([
                    "name" => $cellar->name,
                    "parent" => $request->input('parent'),
                    "buttons" => ['add' => false, 'buy'=> true, 'conf'=> true, 'delete' =>true, 'edit'=> true, 'transf' => true],
                    "icon" => 'cellar',
                    "action" => true
                ]); 
        }else{
            $cellar->warehouse()->update([
                    "name" => $cellar->name
                ]); 
        }
        
    }
    
    public function warehouseForm(Request $request){
        $warehouse = Warehouse::find($request->input('id'));
        $state = null;
        if(isset($warehouse->warehousable_type) && $warehouse->warehousable_type == 'App\Headquarter'){
            $warehouse->with('warehousable.cities', 'warehousable.zones');
            $state     = $warehouse ? State::find($warehouse->warehousable->cities->state_id) : null;
        }
        
        $parent    = Warehouse::find($warehouse ? $warehouse->parent : $request->input('parent'));
        $type      = $warehouse ? $this->getTypeByObject($warehouse) : $this->getTypeByParent($parent);
        $edit      = $warehouse ? true : false;
        $form      = view('inventory.forms.warehouseForm',compact(['warehouse','parent','type','state']))->render();

        return response([
            'success' => true, 
            'view'  => $form,
            'edit'  => $edit
            ]);
    }
    
    public function states(Request $request, $country){
        $query = State::select(['id', 'name as text'])
                ->where('country_id',$country);
        
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $states = $query->limit(100)->get();
        return response(['results' => $states]);
    }
    
    public function cities(Request $request, $state){
        $query = City::select(['id', 'name as text'])
                ->where('state_id',$state);
        
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $cities = $query->limit(100)->get();
        return response(['results' => $cities]);
    }
    
    public function zones(Request $request, $city){
        $query = Zone::select(['id', 'name as text']);
        
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $zones = $query->limit(100)->get();
        return response(['results' => $zones]);
    }
    
    private function validateForm(Request $request){
        $niceNames = [
            'city_id' => 'Ciudad',
            'zones[]' => 'Zonas de cobertura',
            'address' => 'Dirección',
            'name' => 'Nombre'
        ];
        $inputs = [];
        
       
            $inputs = [
                'address'   => 'required',
                'name'      => !is_null($request->input('id_warehouse')) ? 'required|unique:warehouses,name,' . $request->input('id_warehouse') : 'required|unique:warehouses'
            ];
        
        
        $request->validate($inputs,[], $niceNames);
    }
     
    private function getTypeByParent($parent){
        $type =[];
        switch ($parent->warehousable_type){
            case 'App\Headquarter':
                $type = ['action'=> 'inventory/save-cellar','label' => 'Bodega'];
                break;
            default:
                $type = ['action'=> 'inventory/save-headquarter','label' => 'Sede'];
                break;
        }
        return $type;
    }
    
    private function getTypeByObject($object){
        $type =[];
        switch ($object->warehousable_type){
            case 'App\Headquarter':
                $type = ['action'=> 'inventory/save-headquarter','label' => 'Sede'];
                break;
            default:
                $type = ['action'=> 'inventory/save-cellar','label' => 'Bodega'];
                break;
        }
        return $type;
    }
    
    public function deleteForm(Request $request){
        $warehouse = Warehouse::find($request->input('id'));
        $form      = view('inventory.forms.deleteForm', compact('warehouse'))->render();

        return response(['success' => true, 'view' => $form]);

    }













    
    public function softDelete(Request $request){
        $id         = $request->input('id');
        $warehouse  = Warehouse::find($id);
        $continue = $this->verifyChilds($warehouse);
        
        if(!$continue) return response(["success" => false, "message" => $this->msgWarehouseChilds]); 
        
        $this->deleteWarehouseRelated($warehouse);
        $note       = Warehouse::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgWarehouseDeleted : $this->msgError]); 
    }
    
    private function deleteWarehouseRelated($warehouse){
        switch($warehouse->warehousable_type){
            case 'App\Headquarter':
                $headquarter = Headquarter::destroy($warehouse->warehousable_id);
                break;
            case 'App\Cellar':
                $headquarter = Cellar::destroy($warehouse->warehousable_id);
                break;
        }
    }
    // Eliminar compra
    public function softDeleteSopping(Request $request)
    {
        try {
            DB::beginTransaction();
            
                $id     = $request->input('id');
                $purchase   = Purchase::find($id);
                foreach ($purchase->detailPurchases as $purchaseDetail){
                    $productInventori = Inventory::where('warehouse_id',$purchase->warehouse_id)->where('product_id',$purchaseDetail->product_id)->first();
                    if(filled($productInventori)){
                        $productInventori->stock-=$purchaseDetail->stock;
                        $productInventori->save();
                        if($productInventori->stock < 0){
                            abort(406,"No es posible eliminar esta compra, porque el Stock del producto, '".$productInventori->product->name."', es menor a la cantidad de la compra");
                        }elseif($productInventori->stock === 0) $productInventori->delete();
                        $detailsInventori=$productInventori->detailInventories->where('warehouse_id',$purchase->warehouse_id)->where('product_id',$purchaseDetail->product_id)->where('plot',$purchaseDetail->plot)->first();
                        

                        if(filled($productInventori)){
                            $detailsInventori->stock-=$purchaseDetail->stock;
                            $detailsInventori->save();
                            if($detailsInventori->stock < 0){
                                abort(406,"No es posible eliminar esta compra, porque el Stock del producto ".$purchaseDetail->product->name." y lote '$purchaseDetail->plot', es menor a la cantidad del inventario actual");
                            }elseif($detailsInventori->stock === 0) $detailsInventori->delete();
                        }else{
                            abort(406,"No es posible eliminar esta compra, porque el Stock del producto ".$purchaseDetail->product->name." y lote '$purchaseDetail->plot', No existe");
                        }
                    }else{
                        abort(406,"No es posible eliminar esta compra, porque no existe el producto en la bodega");

                    }

                }
                $note=$purchase->delete();
                return response(["success" => 'true', "message" => $note ? $this->msgDeletedSopping : $this->msgError]); 

            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();
            return false;
        } 
    }

    public function softDeleteSettings(Request $request)
    {
        $id     = $request->input('id');
        $note   = ProductWarehouseConfig::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeletedSettings : $this->msgError]); 
    }






    
    private function verifyChilds($warehouse){
        $childs = Warehouse::where('parent',$warehouse->id)->count();
        return $childs > 0 ? false : true;
    }
  
}