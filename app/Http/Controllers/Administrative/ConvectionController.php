<?php

namespace App\Http\Controllers\Administrative;

use App\CategoryConvention;
use App\Convection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConvectionController extends Controller
{

    private $msgError = "";
    public function index(){
        return view('convection.index');
    }

    public function list(){
        $convections = Convection::with(['products','user'])->enabled()->get();
        $crm = "";
        $list = view('convection.sub.list',compact(['convections']))->render();
        return response(['success' => true, 'view' => $list]);
    }

    public function form(Request $request){
        $id = $request->input('id');
        $convection = Convection::with('products')->find($id);
        $title = $request->input('id')?'EDITAR CONVENCIÓN':'CREAR CONVENCIÓN';
        $form = view ('convection.forms.convectionForm',compact('convection','title'))->render();
        return response (['success'=>true, 'view' => $form,  'title' =>  $title ]);

    }

    public function products(Request $request){
        $query = Product::where('category_id',3)->enabled();

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function category(Request $request){
        $query = CategoryConvention::select(['id', 'name as text'])->orderBy('id', 'desc');

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function addProductRow(Request $request){
        $product            = null;
        $key                = uniqid();
        $row       = view('convection.forms.frames.product_new_row',compact(['key','product']))->render();
        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    public function store(Request $request){
        $convection  = new Convection();
        $success = $this->save($request,$convection);
        return response(['success' => $success, 'message' => $success ? 'Convección creada correctamente' : 'Se presentó un error: '.$this->msgError]);
    }

    public function update(Request $request){
        $convection  = Convection::find($request->input('id'));
        $success = $this->save($request,$convection);
        return response(['success' => $success, 'message' => $success ? 'Convección editada correctamente' : 'Se presentó un error: '.$this->msgError]);
    }

    private function save(Request $request, Convection $convection){
        $this->validateForm($request);
        try{
            DB::beginTransaction();
                $convection->name                       = $request->input('name');
                $convection->slug                       = $request->input('slug');
                $convection->category_conventions_id    = $request->input('category_convention');
                $convection->color                     =  $request->input('color');
                $convection->updated_user               = Auth::user()->id; 
                $convection->headquarter_id             = session('headquarter_id');
                $convection->save();
                $convection->products()->sync($this->makeSyncData($request->input('products')));
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            $this->msgError = $ex->getMessage();
            return false;
        }
        
    }

    private function makeSyncData($products){
        $data = [];
        foreach ($products as $key => $product) {
            $data[$product['product']] = ['quantity' => $product['quantity']];
        }
        return $data;
    }

    private function validateForm(Request $request){
            $niceNames= [
                "name"                  => "Nombre",
                "slug"                  => "Siglas",
                "category_convention"   => "Categoria",
                "products.*.product"    => "Producto",
                "products.*.quantity"   => "Cantidad"
            ];

            $request->validate([
                "name"                  => "required",
                "slug"                  => "required",
                "category_convention"   => "required",
                "products.*.product"    => "required",
                "products.*.quantity"   => "required|numeric"
            ],[], $niceNames);
    }

    public function deleteForm(Request $request){
        $id              = $request->input('id');
        $convection      = Convection::find($id);
        $form            = view('convection.forms.deleteForm', compact('convection'))->render();

        return response(['success' => true, 'view' => $form]);

    }
    
    public function softDelete(Request $request){
        $id     = $request->input('id');
        $convection   = Convection::destroy($id);
        return response(["success" => 'true', "message" => $convection ? "Convección eleminada" : $this->msgError]); 
    }
}
