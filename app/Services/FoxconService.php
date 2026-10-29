<?php

namespace App\Services;

use App\FileExtend;
use App\FoxconDocument;
use App\InventoryExpense;
use App\Product;
use App\User;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoxconService {

    private function getProductFoxcon($id){ 
        $product= Product::find($id);
        $code = $product->code;
        if(!$code) abort(406,"Por favor, configure el codigo Foxcon para el producto $product->name");
        return $code;
    }

    private function getDocumentFoxcon($user_id, $type_id){ 
        $warehouse= Warehouse::where('warehousable_type','App\User')->where('warehousable_id',$user_id)->first();
        $result = optional(optional(optional(optional($warehouse)->parentt)->configurationFoxcon)->where('document_type_id', $type_id))->last();
        if(!$result) abort(406,"Para registrar la transacción en Foxcon, debe configurar los tipos de documentos foxcon, para la bodega > ".optional(optional($warehouse)->parentt)->name ?? '');
        return $result;
    }

    private function getDocumentFoxconBywarehouse($warehouse_id, $type_id){ 


        $warehouse = Warehouse::find($warehouse_id);
        if($warehouse->warehousable_type == 'App\Cellar' || $warehouse->warehousable_type == 'App\Office' || $warehouse->warehousable_type == 'App\User' || $warehouse->warehousable_type == 'App\SharedMechasnims'){
            $parent = Warehouse::find($warehouse->parent);
            $result = optional($parent->configurationFoxcon()->where('document_type_id', $type_id)->orderBy('created_at','desc'))->first();
        }else{
            $result = optional($warehouse->configurationFoxcon()->where('document_type_id', $type_id)->orderBy('created_at','desc'))->first();
        }
        if(!$result) abort(406,"Para registrar la transacción en Foxcon, debe configurar los tipos de documentos foxcon, para la bodega > ".$warehouse->name ?? '');
        return $result;
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

    private function getUserIdFoxcon($id){

        $user=User::find($id);
        $id_foxcon=$user->headquarter->foxcon_user_id;
        $name=$user->headquarter->name;

        if(!$id_foxcon) abort(406,"Por favor, configure el id usuario foxcon de compras para la sede > ".$name);

        return $id_foxcon;

    }

    private function getNitFoxcon( $id){ 
        $idd= $id == 1 ? 2 : $id;
        $warehouse= Warehouse::find($idd);
        $nit=null;
        if($warehouse->warehousable_type == 'App\Headquarter') $nit=$warehouse->warehousable->nit;
        if($warehouse->warehousable_type == 'App\User' || $warehouse->warehousable_type == 'App\SharedMechasnims' || $warehouse->warehousable_type == 'App\Office') $nit=$warehouse->parentt->warehousable->nit;
        if(!$nit) abort(406,"Para registrar la transacción en Foxcon, es necesario configurar el NIT para la sede $warehouse->name");
        return $nit;
    }

    private function getWarehouseFoxcon($id){ 
        $warehouse= Warehouse::find($id);
        $code_foxcon = $warehouse->code_foxcon;
        if(!$code_foxcon) abort(406,"Por favor, configure el codigo foxcon para bodega $warehouse->name");
        return $code_foxcon;
    }


    public function saveMechanismExpense($id, $product_id, $warehouse_id, $user_id, Request $request= null){

        $foxconDocuments=collect();
        $inventoryExpense = InventoryExpense::find($id);
        if(isset($inventoryExpense)){
            $documentFoxcon=optional($inventoryExpense->foxconDocument);
            if($documentFoxcon->sync == "1") return abort(406,'El inventario a intervenir ya no se encuentra disponible, porque fue syncronizado por Foxcon');
            
            if(isset($documentFoxcon)){
                $detailDocuments=optional($documentFoxcon->details)->first();
                if(isset($detailDocuments)){
                    $detailDocuments->producto = $this->getProductFoxcon($product_id);
                    $detailDocuments->cantidad = 1;
                    $detailDocuments->save();
                }
                return $documentFoxcon->id;
            }
        }else{
            $configFoxcon=$this->getDocumentFoxcon($user_id, 3);
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
                "nit" => $this->getNitFoxcon($warehouse_id),
                "observation" => $request->observation_foxcon ?? null,
                "created_at" => $request->created_at ?? now(),
                "updated_at" => $request->created_at ?? now()

            ]);
            
            $foxconDocuments->details()->create([
                    'documento'          => $configFoxcon->nomenclature,
                    'numero'             => $numero,
                    'timestamp'          => $request->created_at ?? now(),
                    'producto'           => $this->getProductFoxcon($product_id),
                    'bodega'             => $this->getWarehouseFoxcon($warehouse_id),
                    'cantidad'           => 1,
                    'nit'                => $this->getNitFoxcon($warehouse_id),
                    'tipoes'             => "S",
                    'plot'               => strtoupper('N/A')
            ]);
        }
        return optional($foxconDocuments)->id;
    }

    public function saveExpense($products = [], $warehouse_id, $user_id){

        $foxconDocuments=collect();

        $configFoxcon=$this->getDocumentFoxconBywarehouse($warehouse_id, 3);
        $numero=$this->getNumberFoxcon($configFoxcon->consecutive+1);
        $configFoxcon->consecutive = $numero;
        $configFoxcon->save();
        $foxconDocuments = FoxconDocument::create([
            "documento" => $configFoxcon->nomenclature,
            "numero" => $numero,
            "timestamp" => now(),
            "grabacion" => now(),
            "sync" => 0,
            "usuario" => $this->getUserIdFoxcon($user_id),
            "nit" => $this->getNitFoxcon($warehouse_id),
        ]);
        
        foreach ($products as $key => $product) {
            $foxconDocuments->details()->create([
                'documento'          => $configFoxcon->nomenclature,
                'numero'             => $numero,
                'timestamp'          => now(),
                'producto'           => $this->getProductFoxcon($product['product_id']),
                'bodega'             => $this->getWarehouseFoxcon($warehouse_id),
                'cantidad'           => $product['transfer_quantity'],
                'nit'                => $this->getNitFoxcon($warehouse_id),
                'tipoes'             => "S",
                'plot'               => $product['plot']
            ]);
        }
        
        
        return optional($foxconDocuments)->id;
    }


    public function saveReplaceMechanisms($products =[], $exitWarehouse, $inWarehouse, $user_id, Request $request = null){

            $foxconDocuments=collect();

            $configFoxcon=$this->getDocumentFoxcon($user_id, 2);
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
                "nit" => $this->getNitFoxcon($exitWarehouse),
                "observation" => $request->observation_foxcon ?? null,
                "created_at" => $request->created_at ?? now(),
                "updated_at" => $request->created_at ?? now(),
            ]);
            
            foreach ($products as $product_id) {
                $foxconDocuments->details()->create(

                    [
                        'documento'          => $configFoxcon->nomenclature,
                        'numero'             => $numero,
                        "timestamp"          => $request->created_at ?? now(),
                        'producto'           => $this->getProductFoxcon($product_id),
                        'bodega'             => $this->getWarehouseFoxcon($exitWarehouse),
                        'cantidad'           => 1,
                        'nit'                => $this->getNitFoxcon($exitWarehouse),
                        'tipoes'             => "S",
                        'plot'               => strtoupper('N/A')
                        
                    ]
                
                );

                $foxconDocuments->details()->create(
                    [
                        'documento'          => $configFoxcon->nomenclature,
                        'numero'             => $numero,
                        'timestamp'          => now(),
                        'producto'           => $this->getProductFoxcon($product_id),
                        'bodega'             => $this->getWarehouseFoxcon($inWarehouse),
                        'cantidad'           => 1,
                        'nit'                => $this->getNitFoxcon($inWarehouse),
                        'tipoes'             => "E",
                        'plot'               => strtoupper('N/A')
                    ]
                
                );
                
            }

            
        
        return optional($foxconDocuments)->id;
    }

    public function transferMechanisms($inverse =false, $inWarehouse, $outWarehouse, $mechanism_id, $stock=1, $user_id = null, Request $request = null){

        $foxconDocuments=collect();

            $configFoxcon=$this->getDocumentFoxconBywarehouse($inverse ? $inWarehouse :  $outWarehouse, '2');
            $numero=$this->getNumberFoxcon($configFoxcon->consecutive+1);
            $configFoxcon->consecutive = $numero;
            $configFoxcon->save();

            $foxconDocuments = FoxconDocument::create([
                "documento" => $configFoxcon->nomenclature,
                "numero" => $numero,
                "timestamp" => $request->created_at ?? now(),
                "grabacion" => $request->created_at ?? now(),
                "sync" => 0,
                "usuario" => $this->getUserIdFoxcon(($user_id ?? Auth::user()->id)),
                "nit" => $this->getNitFoxcon($inverse ? $inWarehouse :  $outWarehouse),
                "observation" => $request->observation_foxcon ?? null,
                "created_at" => $request->created_at ?? now(),
                "updated_at" => $request->created_at ?? now(),
            ]);

            $mechanism = FileExtend::find($mechanism_id);
            
            foreach ($mechanism->extendible->products as $product) {
                $foxconDocuments->details()->create(

                    [
                        'documento'          => $configFoxcon->nomenclature,
                        'numero'             => $numero,
                        'timestamp'          => $request->created_at ?? now(),
                        'producto'           => $this->getProductFoxcon($product->id),
                        'bodega'             => $this->getWarehouseFoxcon($outWarehouse),
                        'cantidad'           => $stock,
                        'nit'                => $this->getNitFoxcon($inverse ? $inWarehouse :  $outWarehouse),
                        'tipoes'             => "S",
                        'plot'               => strtoupper('N/A')
                    ]
                
                );

                $foxconDocuments->details()->create(
                    [
                        'documento'          => $configFoxcon->nomenclature,
                        'numero'             => $numero,
                        'timestamp'          => now(),
                        'producto'           => $this->getProductFoxcon($product->id),
                        'bodega'             => $this->getWarehouseFoxcon($inWarehouse),
                        'cantidad'           => $stock,
                        'nit'                => $this->getNitFoxcon($inWarehouse),
                        'tipoes'             => "E",
                        'plot'               => strtoupper('N/A')
                    ]
                
                );
                
            }

            
        
        return optional($foxconDocuments)->id;

    }
}
