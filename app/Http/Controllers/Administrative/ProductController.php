<?php

namespace App\Http\Controllers\Administrative;


use Exception;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Unit;
use App\Category;
use App\Service;
use App\Application;
use App\Mechanism;
use App\Substance;
use App\Infestation;
use App\DetailProduct;
use App\File;
use App\FileType;
use App\MethodProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateProductRequest;

class ProductController extends Controller
{

    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgSuccess = "Producto agregado correctamente";
    public $msgUpdated = "Producto actualizado satisfactoriamente";
    public $msgDeleted = "Producto eliminado satisfactoriamente";
    public $msgDeletedDocument = "Archivo eliminado satisfactoriamente";
    public $msgDeletedMethod = "Metodo eliminado satisfactoriamente";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('product.index');
    }

    public function list() {
        $products = Product::with(['units','applications','categorys','servicios'])
                ->orderBy('id', 'desc')
                ->get();
        $list = view('product.sub.list', compact('products'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    public function formProduct(Request $request)
    {
        $id = $request->input('id');
        $product = Product::with(['files','methods'])->find($id);
        $form = view ('product.forms.productForm', compact('product'))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    public function detailForm(Request $request){
        $id        = $request->input('id');
        $product = Product::with(['units','applications','categorys','mechanisms','servicios'])->find($id);
        $form     = view('product.forms.detailForm',compact(['product']))->render();
        return response(['success' => true, 'view' => $form, 'showMechanism' => (isset($product->categorys) && $product->categorys->id == 3) ? true : false ]);

    }

    public function specificationForm(Request $request){
        $id        = $request->input('id');
        $form      = view('product.forms.specificationForm')->render();
        return response(['success' => true, 'view' => $form]);
    }

    public function methodForm(Request $request){
        $id        = $request->input('id');
        $product   = Product::with(['methods'])->find($id);
        $form      = view('product.forms.methodForm', compact(['product']))->render();
        return response(['success' => true, 'view' => $form]);
    }

    public function fileForm(Request $request){
        $id        = $request->input('id');
        $document  = null;
        $product   = Product::with(['files' => function($q){  }])->find($id);
        $filetypes = FileType::where('slug','product')->enabled()->get();
        $form      = view('product.forms.fileForm',compact(['document','product','filetypes']))->render();
        return response(['success' => true, 'view' => $form]);
    }

    public function units(Request $request){
        $query = Unit::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $units = $query->get();
        return response(['results' => $units]);
    }

    public function application(Request $request){
        $query = Application::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $applications = $query->get();
        return response(['results' => $applications]);
    }

    public function category(Request $request){
        $query = Category::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $categorys = $query->get();
        return response(['results' => $categorys]);
    }

    public function mechanism(Request $request){
        $query = Mechanism::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $mechanisms = $query->get();
        return response(['results' => $mechanisms]);
    }

    public function service(Request $request){
        $query = Service::with('detailServices');
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $services = $query->get();
        return response(['results' => $services]);
    }

    public function product(Request $request){
        $query = Infestation::with('product');
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $product = $query->get();
        return response(['results' => $product]);
    }

    public function infestations(Request $request) 
    {
        $query = Infestation::enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function relatedProducts(Request $request)
    {
        $query = Product::enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $related_products = $query->get();
        return response(['results' => $related_products]);
    }

    public function addDocumentRow(Request $request){
        $document  = null;
        $key       = uniqid();
        $filetypes      = FileType::where('slug','product')->enabled()->get();
        $row       = view('product.forms.frames.document_new_row',compact(['key','document','filetypes']))->render();
        return response(['success' => true, 'row' => $row]);
    }
    
    public function documentForm(Request $request){
        $id        = $request->input('id');
        $document  = null;
        $key       = uniqid();
        $filetypes      = FileType::where('slug','product')->enabled()->get();
        $form      = view('product.forms.fileForm',compact(['key','document','filetypes']))->render();

        return response(['success' => true, 'view' => $form]);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $product = new Product;
        $success = $this->save($request, $product);

        return response(["success" => $success, "message" => $success ? $this->msgSuccess : $this->msgError]);

    }

    public function save(Request $request, Product $product, $action ='create'){
        $this->validateForm($request, $product);
        
        
        try {
            DB::beginTransaction();
            $unit=null;
            if(!is_numeric($request->input('unit'))){
                $unit = Unit::firstOrCreate(['name' => $request->input('unit')]);
            } else{
            $unit = Unit::find($request->input('unit'));
            }
        
            $application=null;
            if(!is_numeric($request->input('application'))){
                $application = Application::firstOrCreate(['name' => $request->input('application')]);
            } else{
                $application = Application::find($request->input('application'));
            }
        
            $mechanism=null;
            if(!is_numeric($request->input('mechanism'))){
                $mechanism = Mechanism::firstOrCreate([ 'name' => $request->input('mechanism'), 'avatar' => $this->getAvatar($request,'avatar_mechanism')]);
            } else{
                $mechanism = Mechanism::find($request->input('mechanism'));
                $mechanism->avatar = $this->getAvatar($request,'avatar_mechanism');
                $mechanism->save();
            }

            
            $product->avatar = $this->getAvatar($request);
            $product->name = $request->input('name');
            $product->code = $request->input('code');
            $product->enabled = $request->input('enabled')? true : false;
            $product->description = $request->input('description');
            $product->capture_surface = $request->input('capture_surface');
            $product->unit_id = $unit->id;
            //$product->application_id = $application->id;
            $product->category_id = $request->input('category');
            $product->mechanism_id = $mechanism->id;
            $product->toxico = $request->input('toxico')? true : false;
            $product->color = $request->input('color');
            $product->price = $this->clear_format($request->input('price'));
            $product->save();
            $product->infestations()->sync($request->input('infestations'));
            $product->products()->sync($request->input('related_products'));
            //$product->infestations()->sync($request->input('infestation'));
        
            foreach ($request->input('specificationProduct') as $key => $detail) {

            DetailProduct::updateOrCreate(
                [
                    'id'=> $detail['id']
                    ],
                [
                    'product_id' => $product->id,
                    'key'=>$detail['key'], 
                    'value'=>$detail['value'], 
                    'enabled'=>$detail['enabled']
                    ] 
                );
            }

            $apiProduct = \App\Models\Api\Product::find($product->id);


            //almacenar todos los archivos del producto
            $this->saveFile($request, $product, 'products', 'file' ,true, 'document', 'method', $apiProduct);

            //almacenar todas los metodos asociados
            $this->saveMethods($request, $product, $action);
                  
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();
            return false;
        }

    }

    private function saveMethods(Request $request, Product $product){
        if($request->filled('method')){
            foreach ($request->all()['method'] as $method) {
            
                $method = new Request($method);
                if($method->filled('name')){
                    if($method->filled('id')){
    
                        $bind = [
                            "name" => $method->input('name'),
                            "doses" => $method->input('doses'),
                            "description" => $method->input('description')
                        ];
        
                        //condicionar si la imagen cambia
                        if($method->has('avatar')){
                            $bind["avatar"] = $this->getAvatarFromBinary($method);
                        }
                        
        
                        $product->methods()->where("id",$method->input('id'))->update($bind);
        
                    }else{
                        $product->methods()->create([
                            "name" => $method->input('name'),
                            "doses" => $method->input('doses'),
                            "description" => $method->input('description'),
                            "avatar" => $this->getAvatarFromBinary($method),
                        ]);
                        
                    }
                }
                
            }
        }
    
}

    private function validateForm(Request $request, Product $product) {

        $niceNames = [
            'name' => 'Nombre',
            'category' => 'Categoría',
            'code' => 'Codigo',
            'document.*.file' => 'Archivo',
            'document.*.type_id' => 'Tipo',
            'document' => 'una fila de documentos',
            'method'  => 'una fila de métodos',
            'method.*.name' => 'Nombre'
        ];
        
        $request->validate([
            "category" => 'required',
            "name" => 'required',
            'document.*.type_id'    => 'required_with:document',
            'document.*.file'       => 'mimes:jpeg,png,pdf,docx,xls,xlsx,csv|required_with:document',
            'method.*.name'         =>  'required_with:method'
        ], 
        [], $niceNames);
            
    }


    /**
     * Display the specified resourcen.
     *n
     * @param  \App\Product  $productn
     * @return \Illuminate\Http\Responnse
     */
    public function show(Product $product)
    {
        //
    }

    public function update(Request $request)
    {

        $id        = $request->input('id');
        $product   = Product::find($id);
        $success   = $this->save($request, $product);

    
        return response(["success" => $success, "message" => $success ? $this->msgUpdated : $this->msgError]);
    }

    public function deleteForm(Request $request){
        $id        = $request->input('id');
        $product   = Product::find($id);
        $form      = view('product.forms.deleteForm', compact('product'))->render();

        return response(['success' => true, 'view' => $form]);

    }

    public function deleteDocumentoForm(Request $request){
        $id        = $request->input('id');
        $document  = File::find($id);
        $form      = view('product.forms.deleteFormDocument', compact('document'))->render();

        return response(['success' => $document? true: false, 'view' => $form]);
    }
    
    public function softDelete(Request $request)
    {
        $id     = $request->input('id');
        $note   = Product::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeleted : $this->msgError]); 
    }

    public function softDeleteDocument(Request $request)
    {
       $id  = $request->input('id');
        $success   = File::destroy($id);
              $s2  = \App\Models\Api\File::destroy($id+1);
        return response(["success" => $success, "message" => $success ? $this->msgDeletedDocument : $this->msgError]); 
    }

    public function showFile(Request $request){
        $id = $request->input('id');
        $file = DocumentationProduct::find($id);
        return $this->OpenFile($file);
    }

    /**
     * Añadir nueva fila al formulario de imagenes metodos
     */
    public function addMethodRow(Request $request){
        $methods    = null;
        $key       = uniqid();
        $row       = view('product.forms.frames.method_new_row',compact(['key','methods']))->render();
        return response(['success' => true, 'row' => $row]);
    }

    /**
     * Ocultar fila formulario imagenes metodos
     */
    public function deleteMethodForm(Request $request){
        $id        = $request->input('id');
        $methods   = MethodProduct::find($id);
        $form      = view('product.forms.deleteFormMethod', compact('methods'))->render();
        return response(['success' => $methods ? true: false, 'view' => $form]);
    }

    /**
     *   Eliminar fila formulario de imagenes metodos
     */
    public function softDeleteMethod(Request $request)
    {
        $id  = $request->input('id');
        $success   = MethodProduct::destroy($id);
        return response(["success" => $success, "message" => $success ? $this->msgDeletedMethod : $this->msgError]); 
    }
}
