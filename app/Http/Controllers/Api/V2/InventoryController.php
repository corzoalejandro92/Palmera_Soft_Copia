<?php

namespace App\Http\Controllers\Api\V2;

use App\ControlInfestation;
use App\Http\Resources\InventoryResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\InfestationResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReportTypeResource;
use App\Http\Resources\RouteResource;
use App\Inventory;
use App\Product;
use App\Route;
use App\Services\Api\V2\InventoryService;
use App\TechnicalReportType;
use App\TransferInventory;
use App\User;
use Illuminate\Support\Facades\Auth;


class InventoryController extends Controller
{

    public InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }
   

    public function index(Request $request){

        return response()->json(
            [
                "success" => true,
                "data" => [
                    "inventory"  => $this->inventoryService->getByUser($request),
                ]
            ]
        );
    }

    public function pendingToConfirm(Request $request){
        
        return response()->json(
            [
                "success" => true,
                "data" => [
                    "transfers"  => $this->inventoryService->getPendingToConfirm($request),
                ]
            ]
        );
    }

    public function acceptTransfer(Request $request){

        $this->inventoryService->acceptTransfer($request->input('transfer_id'));
        
        return response()->json(
            [
                "success" => true,
                "data" =>[
                    "inventory"  => $this->inventoryService->getByUser($request),
                    "transfers"  => $this->inventoryService->getPendingToConfirm($request),
                ]
            ]
        );
    }



}
