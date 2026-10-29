<?php

use App\FoxconDocument;
use App\Inventory;
use App\Product;
use App\Qr;
use App\User;
use App\Warehouse;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateWarehouseSharedInventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '300');
            DB::beginTransaction();
                $qrs = Qr::has('extend')->orderBy('id','desc')->get();
                foreach ($qrs as $qr){
                    $products               = optional($qr->extend->extendible)->products;
                    $file                   = optional(optional($qr->extend)->fileTrashed);//->filable)->office)->headquarter; 
                    if(isset($file)){
                        $areaOffice             = \App\AreaOffice::withTrashed()->find($file->filable_id);
                        if(!isset($areaOffice)) dd("No se encontro Area para el FileExtend > ".$qr->file_extend_id .', QR '. $qr->token);
                        $headquarter            = optional(optional($areaOffice)->officeTrashed)->headquarter;
                        if(!isset($headquarter)) dd("No se encontro la Sede para el FileExtend > ".$qr->file_extend_id);
                        $warehouse              = $headquarter->warehouse; 
                        $warehouseShared        = Warehouse::where('parent',$warehouse->id)->where('warehousable_type','App\SharedMechasnims')->first();
                    }else{
                        dd("No se encontro el archivo para el FileExtend > ".$qr->file_extend_id);
                    }

                    $transferProd               = Array();
                    $productCountInventorys     = 1;

                    foreach ($products as $product){

                        $productCountInventorys = Inventory::where('warehouse_id',$warehouseShared->id)
                        ->where('product_id',$product->id)
                        ->where('file_extend_id',$qr->file_extend_id)
                        ->count();
                        
                        if($productCountInventorys == 0){
                            $transferProd[] = [
                                "product_id"            => $product->id,
                                "plot"                  => "NA",
                                "code"                  => $product->code,
                                "cost"                  => $product->cost,
                                "quantity"              => optional($product->pivot)->quantity,
                                "transfer_quantity"     => optional($product->pivot)->quantity,
                            ];
                        }
                    }
                    
                    if($productCountInventorys == 0){

                        $req = [
                            'document_type_id'      => $this->getDocumentFoxconTraWarehouse(optional($warehouse)->id),
                            'headquarter_id'        => $headquarter->id,
                            'user_id'               => 1,
                            'file_extend_id'        => $qr->file_extend_id,
                            "warehouse_id"          => optional($warehouse)->id,
                            "warehouse_transfer"    => optional($warehouseShared)->id,
                            "transferInventory"     => $transferProd,
                            'timestamp'             => $qr->scan_date
                        ];
                        $newRequest = new Request($req);
                        $this->saveInventaryDirectly($newRequest);
                    }

                }
            DB::commit();
        } catch (Exception $ex) {
            dd(["mensaje" => $ex->getMessage(),"line" => $ex->getLine()]);
        }
    }

    private function getDocumentFoxconTraWarehouse($id){ 
        $warehouse = Warehouse::find($id);
        if($warehouse->warehousable_type == "App\SharedMechasnims" || $warehouse->warehousable_type == "App\User") {
            $warehouse=$warehouse->parentt;
        }
        $result = optional(optional(optional($warehouse)->configurationFoxcon)->where('document_type_id', 2))->last();
        if(!$result) dd("Para registrar el Traslado en Foxcon, debe configurar los Traslados de documentos de foxcon, para la bodega > ".optional($warehouse)->name ?? '');
        return $result->id;
    }

    private function getNitFoxcon( $id){ 
        $idd= $id == 1 ? 2 : $id;
        $warehouse= Warehouse::find($idd);
        $nit=null;
        if($warehouse->warehousable_type == 'App\Headquarter') $nit=$warehouse->warehousable->nit;
        if($warehouse->warehousable_type == 'App\User' || $warehouse->warehousable_type == 'App\SharedMechasnims') $nit=$warehouse->parentt->warehousable->nit;
        if(!$nit) dd("Para registrar la transacción en Foxcon, es necesario configurar el NIT para la sede > $warehouse->name");
        return $nit;
    }

    private function getProductFoxcon($id){ 
        $product= Product::find($id);
        $code = $product->code;
        if(!$code) dd("Por favor, configure el codigo Foxcon para el producto > $product->name");
        return $code;
    }

    private function getUserIdFoxcon($id){ 
        $user=User::find($id);
        $id_foxcon=$user->headquarter->foxcon_user_id;
        $name=$user->headquarter->name;
        
        if(!$id_foxcon) dd("Por favor, configure el id usuario foxcon de compras para la sede > ".$name);
        return $id_foxcon;
    }

    private function getWarehouseFoxcon($id){ 
        $warehouse= Warehouse::find($id);
        $code_foxcon = $warehouse->code_foxcon;
        if(!$code_foxcon) dd("Por favor, configure el codigo foxcon para bodega > $warehouse->name");
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

    private function getDocumentFoxconTrasladWarehouse($id){ 
        $warehouse = Warehouse::find($id);
        if($warehouse->warehousable_type == "App\SharedMechasnims" || $warehouse->warehousable_type == "App\User") {
            $warehouse=$warehouse->parentt;
        }
        $result = optional(optional(optional($warehouse)->configurationFoxcon)->where('document_type_id', 2))->last();
        if(!$result) dd("Para registrar el Traslado en Foxcon, debe configurar los Traslados de documentos de foxcon, para la bodega > ".optional($warehouse)->name ?? '');
        return $result;
    }
    
    
    //PERMITE AGREGAR LA TRANFERENCIA DIRECTAMENTE EN EL INVENTARIO
    private function saveInventaryDirectly(Request $request){
       
        if($request->has('transferInventory')){
            foreach ($request->input('transferInventory') as $indx => $content) {
                $content = new Request($content);
                $productInventory=null;

                $productInventorys = Inventory::where('warehouse_id',$request->input('warehouse_transfer'))
                ->where('product_id',$content->input('product_id'))
                ->where('file_extend_id',$request->input('file_extend_id'))
                ->get();

                foreach($productInventorys as $productInventory){
                    $productInventory->stock += $content->input('transfer_quantity');
                    $productInventory->save();
                }

                $dateExpiration=new Carbon();

                if(count($productInventorys) > 0){


                    $productInventory->detailInventory()->create(
                        [ 
                            'warehouse_id'       => $request->input('warehouse_transfer'),
                            'product_id'         => $content->input('product_id'),
                            'plot'               => $content->input('plot'),
                            'code'               => $content->input('foxcon'),
                            "date_expiration"    => $dateExpiration->addYear(),
                            'stock'              => $content->input('transfer_quantity'),
                        ]);

                  
                }else{

                    $inventory = Inventory::create([
                        'headquarter_id'        => $request->headquarter_id,
                        'warehouse_id'          => $request->input('warehouse_transfer'),
                        'product_id'            => $content->input('product_id'),
                        'stock'                 => $content->input('transfer_quantity'),
                        'file_extend_id'        => $request->input('file_extend_id')
                    ]);

                    $inventory->detailInventory()->create(
                        [ 
                            'warehouse_id'       => $request->input('warehouse_transfer'),
                            'product_id'         => $content->input('product_id'),
                            'plot'               => $content->input('plot'),
                            'code'               => $content->input('foxcon'),
                            "date_expiration"    => $dateExpiration->addYear(),
                            'stock'              => $content->input('transfer_quantity'),
                        ]
                    );
                }
                
            }
        }
        $this->saveFoxconDocumentsTechnical($request);
    }

    //PERMITE GUARDAR LA TRANSFERENCIA EN LA TABLA DE FOXCON CUANDO SE REALIZA LA TRANFERENCIA
    private function saveFoxconDocumentsTechnical(Request $request){
        
        $configFoxcon=$this->getDocumentFoxconTrasladWarehouse($request->warehouse_id);
        $numero=$this->getNumberFoxcon($configFoxcon->consecutive+1);
        $foxconDocuments = FoxconDocument::create([
            "documento" => $configFoxcon->nomenclature,
            "numero" => $numero,
            "timestamp" => $request->input('timestamp'),
            "grabacion" => $request->input('timestamp'),
            "sync" => 0,
            "usuario" => $this->getUserIdFoxcon($request->user_id),
            "nit" => $this->getNitFoxcon($request->warehouse_id),
        ]);
        $configFoxcon->consecutive = $numero;
        $configFoxcon->save();

        if($request->has('transferInventory')){
            foreach ($request->input('transferInventory') as $indx => $content) {
                $content = new Request($content);
                    $foxconDocuments->details()->create([
                            'documento'          => $configFoxcon->nomenclature,
                            'numero'             => $numero,
                            'timestamp'          => now(),
                            'producto'           => $this->getProductFoxcon($content->product_id),
                            'bodega'             => $this->getWarehouseFoxcon($request->warehouse_id),
                            'cantidad'           => $content->transfer_quantity,
                            'nit'                => $this->getNitFoxcon($request->warehouse_id),
                            'tipoes'             => "S",
                            'plot'               => strtoupper('N/A')
                    ]);
                    $foxconDocuments->details()->create([
                            'documento'          => $configFoxcon->nomenclature,
                            'numero'             => $numero,
                            'timestamp'          => now(),
                            'producto'           => $this->getProductFoxcon($content->product_id),
                            'bodega'             => $this->getWarehouseFoxcon($request->warehouse_transfer),
                            'cantidad'           => $content->transfer_quantity,
                            'nit'                => $this->getNitFoxcon($request->warehouse_transfer),
                            'tipoes'             => "E",
                            'plot'               => strtoupper('N/A')
                    ]);
            }
        }
    }
}
