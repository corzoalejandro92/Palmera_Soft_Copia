<?php

namespace App\Http\Controllers\Api\V2;

use App\ControlInfestation;
use App\Crm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\InfestationResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReportTypeResource;
use App\Http\Resources\RouteResource;
use App\Infestation;
use App\Product;
use App\Route;
use App\Services\Api\V2\InventoryService;
use App\TechnicalReportType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\In;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class RouteController extends Controller
{

    public InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request){

        $routes = Route::with([
            'schedulings.crm',
            'schedulings.crmSchedule.office.areas.files.extends.lastReports',
            'schedulings.crmSchedule.service', 
            'schedulings.crmSchedule.controls.area',
            'schedulings.crmSchedule.controls.product.relatedProducts',
            'schedulings.crmSchedule.controls.dose',
            'schedulings.crmSchedule.controls.application',
            'schedulings.crmSchedule.infestationGrades',
            'schedulings.crmSchedule.consumptions.inventoryDetail.inventory',
            'schedulings.crmSchedule.reportedProducts',
            'schedulings.observations',
            'schedulings.signature',
            ])
        ->where('user_id', Auth::id())
        ->orderByDesc('date')->limit(7)->get();

        $groupedActivities = $routes->map(function ($route) {
            $route->customers = $route->schedulings->groupBy('crm_id')
            ->map(function ($activities) {

                $crm = $activities->first()->crm;
                

                return [
                    'id' => $crm ? $crm->id :  0,
                    'name' =>  $crm ? $crm->name : 'Actividades generales',
                    'schedulings_count' => count($activities),
                    'schedulings' => $activities
                ];
            })->values();

            return $route;
        });

       
        
        return response()->json(
            [
                "success" => true,
                "data" => [
                    "routes"        => RouteResource::collection( $groupedActivities),
                    "infestations"  => InfestationResource::collection(ControlInfestation::all()),
                    'products'      => ProductResource::collection(Product::with('unit')->whereIn('category_id',[1,2,3,6])->where('enabled',1)->get()),
                    'reportTypes'   => ReportTypeResource::collection(TechnicalReportType::all()),
                    "inventory"     => $this->inventoryService->getByUser($request),
                ]
            ]
        );
    }
}
