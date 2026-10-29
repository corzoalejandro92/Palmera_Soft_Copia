<?php

namespace App\Http\Controllers;

use App\Cellar;
use App\ConfirmationTransferInventory;
use App\DetailConfirmationTransfer;
use App\DetailGeneralOrderPurchase;
use App\FoxconDocument;
use App\FoxconDocumentDetail;
use App\GeneralOrderPurchase;
use App\Headquarter;
use App\ImportFoxcon;
use App\Imports\FoxconSheetImport;
use App\Inventory;
use App\InventoryDetail;
use App\Office;
use App\OrderPurchase;
use App\OrderPurchaseDetail;
use App\SharedMechasnims;
use App\TransferInventory;
use App\TransferInventoryDetail;
use App\User;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ImportFoxconController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('importFoxcon.index');
    }

    public function bnacional()
    {
        return view('importFoxcon.bnacional');
    }


    public function upload(Request $request){


       

        //alamacenar el archivo que se intentó importar
        try {

            if($request->headquarter_id != session('headquarter_id')){
                dd('Error de cambio de sede - No se puede realizar la importación en esta sede ('.session('headquarter_name').')');
            }else{
                Log::channel('foxcon-import')->info('Inicio de importación de saldos en la sede: '.session('headquarter_name'));
            }

            //validar que la integracion este activa para esta sede
            if(!(\App\Integration::find(1)->enabled && 
            in_array(
                \App\Headquarter::find(
                    session('headquarter_id')
                )->id , \App\Integration::find(1)->headquarters
            ))){
                dd('No se puede realizar la importación porque la integración foxcon no ha sido habilitada en esta sede ('.session('headquarter_name').')');
            }


            $request->validate([
                'file' => 'required|mimes:xlsx'
            ]);

            //se establece memoria infinita para evitar fallos de colapso de memoria
            ini_set('memory_limit', '-1');

            $file = $request->file('file');

            DB::beginTransaction();

             //truncar tablas de inventario si la opción viene activada
            if($request->filled('truncate')){
                if($request->input('truncate') == 1){
                    artisan::call('db:seed', array('--class' => "TruncateInventoryTables"));
                }else{
                    $this->cleanOnlyThisHeadquarter();
                }
            }

                $import = new ImportFoxcon();
                $import->user_id = Auth::User()->id;
                $import->date = date('Y-m-d H:i:s');
                $import->save();
                $fileResponse = $this->saveFile($request, $import, 'public/imports');


                Log::channel('foxcon-import')->info('Ruta del archivo cargado: '. $fileResponse->path);
                Excel::import(new FoxconSheetImport(),$fileResponse->path);
                Log::channel('foxcon-import')->info('Importación finalizada');
            DB::commit();

            //si la opcion de homologar mecanismos instalados es enviada
            if($request->filled('approved_loans')){
                Artisan::call('db:seed', array('--class' => "UpdateWarehouseSharedInventoryTableSeeder"));
            }
            return view('importFoxcon.success');
            
        } catch (\Exception $th) {
            DB::rollBack();
            echo 'upss';
            echo $th->getMessage();
            print_r('<pre>');
            print_r($th->getTrace());
            print_r('<pre>');
            die();
        }
        
    }

    public function bnacionalUpload(Request $request){


       

        //alamacenar el archivo que se intentó importar
        try {

            
            //validar que la integracion este activa para esta sede
            if(!(\App\Integration::find(1)->enabled && 
            in_array( "99999999" , \App\Integration::find(1)->headquarters))){
                dd('No se puede realizar la importación porque la integración foxcon no ha sido habilitada en la bodega nacional.');
            }


            $request->validate([
                'file' => 'required|mimes:xlsx'
            ]);

            //se establece memoria infinita para evitar fallos de colapso de memoria
            ini_set('memory_limit', '-1');

            $file = $request->file('file');

            DB::beginTransaction();

            Log::channel('foxcon-import')->info('Inicio de importación de saldos en bodega nacional');

             //truncar tablas de inventario si la opción viene activada
            if($request->filled('truncate')){
                $this->cleanOnlyBNacional();
            }

                $import = new ImportFoxcon();
                $import->user_id = Auth::User()->id;
                $import->date = date('Y-m-d H:i:s');
                $import->save();
                $fileResponse = $this->saveFile($request, $import, 'public/imports');


                Log::channel('foxcon-import')->info('Ruta del archivo cargado: '. $fileResponse->path);
                Excel::import(new FoxconSheetImport(),$fileResponse->path);
                Log::channel('foxcon-import')->info('Importación finalizada');
            DB::commit();

            return view('importFoxcon.success');
            
        } catch (\Exception $th) {
            DB::rollBack();
            echo $th->getMessage();
            print_r('<pre>');
            print_r($th->getTrace());
            print_r('<pre>');
            die();
        } catch (Throwable $e) {
            Log::channel('foxcon-import')->error('Error inesperado durante la importación: ' . $e->getMessage());
            return response()->json(['error' => 'Error inesperado durante la importación'], 500);
        }
        
    }


    public function cleanOnlyThisHeadquarter(){

        //obtener todos los warehouse id de la sede
     
        $warehouses = Warehouse::
        whereHasMorph('warehousable', [Headquarter::class], function($query){
            $query->where('id', session('headquarter_id'));
        })->orWhereHasMorph('warehousable', [Office::class, User::class, SharedMechasnims::class, Cellar::class], function($query){
            $query->where('headquarter_id', session('headquarter_id'));
        })->get();

        $warehousesId = $warehouses->pluck('id');
        $warehousesCodes = $warehouses->pluck('code_foxcon')->unique()->filter();
        


        //tablas de foxcon
        $foxconDetailsDocuments = FoxconDocumentDetail::whereIn('bodega', $warehousesCodes)->get();
        if($foxconDetailsDocuments->count() > 0){
            $foxconDocumentIds = $foxconDetailsDocuments->pluck('foxcon_document_id')->unique();
            FoxconDocumentDetail::whereIn('foxcon_document_id',$foxconDocumentIds)->delete();
            FoxconDocument::whereIn('id', $foxconDocumentIds)->delete();
        }

        InventoryDetail::whereIn('warehouse_id',$warehousesId)->delete();
        Inventory::whereIn('warehouse_id',$warehousesId)->delete();
        
        GeneralOrderPurchase::where('headquarter_id', session('headquarter_id'))->delete();
        OrderPurchase::where('headquarter_id', session('headquarter_id'))->delete();
        TransferInventory::where('headquarter_id', session('headquarter_id'))->delete();
        ConfirmationTransferInventory::where('headquarter_id', session('headquarter_id'))->delete();
    }

    public function cleanOnlyBNacional(){

        //obtener todos los warehouse id de la sede
     
        $warehouses = Warehouse::where('id',1)->get();

        $warehousesId = $warehouses->pluck('id');
        $warehousesCodes = $warehouses->pluck('code_foxcon')->unique()->filter();

        //tablas de foxcon
        $foxconDetailsDocuments = FoxconDocumentDetail::whereIn('bodega', $warehousesCodes)->get();
        if($foxconDetailsDocuments->count() > 0){

            $foxconDocumentIds = $foxconDetailsDocuments->pluck('foxcon_document_id')->unique();
            FoxconDocumentDetail::whereIn('foxcon_document_id',$foxconDocumentIds)->delete();
            FoxconDocument::whereIn('id', $foxconDocumentIds)->delete();
        }

        InventoryDetail::whereIn('warehouse_id',$warehousesId)->delete();
        Inventory::whereIn('warehouse_id',$warehousesId)->delete();
        
        GeneralOrderPurchase::where('headquarter_id', session('headquarter_id'))->delete();
        OrderPurchase::where('headquarter_id', session('headquarter_id'))->delete();
        TransferInventory::where('headquarter_id', session('headquarter_id'))->delete();
        ConfirmationTransferInventory::where('headquarter_id', session('headquarter_id'))->delete();
    }
}