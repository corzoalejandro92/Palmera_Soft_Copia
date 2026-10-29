<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use App\General\APIResponse;
use App\General\Token;
use App\General\Roles;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Luecano\NumeroALetras\NumeroALetras;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, APIResponse,Token,Roles;

    public $html = '';
    
    protected function saveFile(Request $request, $model, $path ='public/commons', $input="file", $isGroup = false ,$group="document", $duplicateModel = false){
        $response = false;
        if($isGroup){
            if($request->has($group)){
                foreach ($request->input($group) as $indx => $content) {
                    $content = new Request($content);
                     if(isset($request->all()[$group][$indx][$input])){
                        $file = $request->all()[$group][$indx][$input];
                        $response = $this->_process_file($file, $path, $model, $content, $duplicateModel);
                     }else if(isset($request->all()[$group][$indx]['id'])){
                        $response = $model->file()->where('id',$request->all()[$group][$indx]['id'])->update([
                            'type_id'       => $content->input('type_id'),
                            'observation'   => $content->input('observation')
                        ]);
                     }
                }
            }
        } else {
            if(isset($request->all()[$input])){
                $file = $request->all()[$input];
                $response = $this->_process_file($file, $path, $model, $request, $duplicateModel);
            }else if(isset($request->all()['id'])){

                $response = $model->file()->where('id',$request->all()['id'])->update([
                    'type_id'       => $request->input('type_id'),
                    'observation'   => $request->input('observation')
                ]);
             }
        }

        return $response;
    }

    private function _process_file($file, $path, $model,$content,$duplicateModel){
        $response = false; 
        $object = $file->getClientOriginalName();
        $filename = pathinfo($object, PATHINFO_FILENAME);
        $extension = pathinfo($object, PATHINFO_EXTENSION);
        $date = Carbon::now();
        $datefolder = '/'.$date->copy()->format('Y') . "/" . $date->copy()->format('m');
        $response = $model->file()->create([
            'name'          => "{$filename}.{$extension}",
            'path'          => $file->store($path.$datefolder,['disk' => 'public']),
            'type_id'       => $content->input('type_id'),
            'observation'   => $content->input('observation')
        ]);

        if($duplicateModel){
            $response = $duplicateModel->file()->create([
                'name'          => "{$filename}.{$extension}",
                'path'          => $file->store($path.$datefolder,['disk' => 'public']),
                'type_id'       => $content->input('type_id'),
                'observation'   => $content->input('observation')
            ]);
        }

        return $response;
    }

    protected function getAvatar(Request $request,$input ='avatar', $nameFolder = 'avatars', $quality = 50, $size=150) {
        
        if($request->filled($input)){
            $avatar = $request->input($input);
            try {
                $base64 = explode(",", $avatar);
                $image = $base64[1];
                $pos = strpos($avatar, ';');
                $filetype = $this->fileType(explode(':', substr($avatar, 0, $pos))[1]);
                if (!$filetype) {
                    return $avatar;
                }
                $date = Carbon::now();
                $newName = uniqid();
                $path = "{$nameFolder}/" . $date->copy()->format('Y') . "/" . $date->copy()->format('m') . "/" . $newName . "." . $filetype;
                Storage::disk('public')->put($path, base64_decode($image));
                $manager = new ImageManager(array('driver' => 'imagick'));
                $image = $manager->make(Storage::disk('public')->get($path));
                if($size !== false){
                    $image->resize(null, $size, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                

                $image->save(public_path('/storage/'.$path), $quality);

                return "$path";
            } catch (\ErrorException $e) {
                return $avatar;
            }
        }
        
    }

    protected function getFile(Request $request,$input ='image', $_patch = "commons") {
        $_file = $request->input($input);
        try {
            $base64 = explode(",", $_file);
            $file = $base64[1];
            $pos = strpos($_file, ';');
            $filetype = $this->fileType(explode(':', substr($_file, 0, $pos))[1]);
            if (!$filetype) {
                return $_file;
            }
            $date = Carbon::now();
            $newName = uniqid();
            $path = $_patch .'/'. $date->copy()->format('Y') . "/" . $date->copy()->format('m') . "/" . $newName . "." . $filetype;
            Storage::disk('public')->put($path, base64_decode($file));
            $manager = new ImageManager(array('driver' => 'imagick'));
            $file = $manager->make(Storage::disk('public')->get($path));
            $result =  $file->save(storage_path('app/public')."/{$path}", 50);
            if(!$result) {abort(500, 'Cant create file in server folder');}
            return "{$path}";
        } catch (\ErrorException $e) {
            abort(500, $e->getMessage());
        }
    }

    protected function deleteFile($path,$file){
        if(is_file( '/public/storage/'.$file))
        {
            Storage::disk('public')->delete($file);
        }else{
           return false;
        }
    }

    private function fileType($filetype) {
        if ($filetype === "image/jpeg") {
            return "jpeg";
        }
        if ($filetype === "image/gif") {
            return "gif";
        }
        if ($filetype === "image/png") {
            return "png";
        }
        if ($filetype === "image/jpg") {
            return "jpg";
        }
        return false;
    }
    
    protected function openFile($file){
        $path = $file->path;
        if(Storage::disk('public')->exists($path)) {
            $f = Storage::disk('public')->get($path);
            $mimetype = Storage::disk('public')->mimeType($path);
            
            return response($f)
                ->withHeaders([
                    'title'=> $file->name,
                    'Content-Disposition' => 'inline; filename="'.basename($file->name).'"',
                    'Content-Type' => $mimetype,
                ]);
        }else{
            abort(405, 'El archivo que deseas consultar ha sido eliminado o ya no se encuentra disponible');
        }
        
        
    }
    
    protected function clear_format($string){
        //if($string){
          $str = str_replace('.', '', $string);
           return str_replace(',', '', $str);
        //}
    }
      
    protected function getNum($number) {
        return !empty($number) ? $this->clear_format($number) : 0;
    }
    
    protected function isNum($number) {
        return !empty($number) ? $this->clear_format($number) : null;
    }
    
    protected function isBoolean($string){
        return $string === 'true'? true: false;
    }  

    /** Convertir una imagen binaria en base64 */
    protected function getAvatarFromBinary(Request $request,$input ='avatar', $nameFolder = 'avatars'){
        if($request->filled($input)){
            $file = $request[$input];
            $manager = new ImageManager(array('driver' => 'imagick'));
            $avatar = (string) $manager->make($file)->encode('data-url');
            try {
                $base64 = explode(",", $avatar);
                $image = $base64[1];
                $pos = strpos($avatar, ';');
                $filetype = $this->fileType(explode(':', substr($avatar, 0, $pos))[1]);
                if (!$filetype) {
                    return $avatar;
                }
                $date = Carbon::now();
                $newName = uniqid();
                $path = "{$nameFolder}/" . $date->copy()->format('Y') . "/" . $date->copy()->format('m') . "/" . $newName . "." . $filetype;
                Storage::disk('public')->put($path, base64_decode($image));
                $manager = new ImageManager(array('driver' => 'imagick'));
                $image = $manager->make(Storage::disk('public')->get($path));
                $image->resize(null, 500, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image->save(public_path('/storage/'.$path), 50);
                return $path;
            } catch (\ErrorException $e) {
                return $avatar;
            }
    }else{
        return null;
    }
        
    }


    /** Funciones para reemplazar el texto de la cotización */
    
    protected function replaceQuote($quote){
        $this->_replaceText('background-color:null',  "");

        $this->_replaceText('{{crm-name}}', $quote->crm->name);
        $this->_replaceText('{{crm-contact-name}}', $quote->contact->name);
        $this->_replaceText('{{crm-office-address}}', $quote->office->address. ', '.$quote->office->city->name);
        $this->_replaceSimpleService('{{start-quote-service-simple}}','{{end-quote-service-simple}}', $quote->services);
        $this->_replaceIndividualService('{{start-quote-service-individual}}','{{end-quote-service-individual}}', $quote->services);
        $this->_replaceText('{{quote-service-table}}',  view('quote.sub.frames.service_table', compact(['quote']))->render() );
        $this->_replaceText('{{user-headquarter-city}}',  session('headquarter_name') );
        $this->_replaceText('{{crm-office-name}}',  $quote->office->name);

        //solución para propuesta económica
        $this->_replaceProposalService('{{start-quote-services-proposal}}','{{end-quote-services-proposal}}', $quote->services);
        
        $this->_replaceText('{{total-quote-services-value}}', number_format($quote->total,0,'.',',') );
        
        $formatter = new NumeroALetras();
        $this->_replaceText('{{total-quote-services-value-text}}', $formatter->toMoney($quote->total, 0, 'PESOS') );

        $days = Carbon::now()->diffInDays(Carbon::parse($quote->expire_date), false);
        if($days < 0){
            $days = "<b><span style='color:red;'>ESTA COTIZACIÓN EXPIRÓ DESDE EL {$quote->expire_date->format('Y-m-d')}</span></b>";
        }else {
            $days = ($days != 1 ? $days. ' dias': $days. ' dia'); 
        }
        $this->_replaceText('{{quote-expired-difference}}', $days );
        $this->_replaceText('{{user-name}}',  $quote->commercial->name );
        $this->_replaceText('{{hadquarer-name}}',  session('headquarter_name') );
        $this->_replaceText('{{quote-total}}', "$ ".number_format($quote->total,0,'.',',')  . " (COP)");
        $this->_replaceImg('{{user-firm}}',  $quote->commercial->signature );
        $this->_replaceText('{{user-position}}',  $quote->commercial->position );
        $this->_replaceText('{{user-phone}}',  $quote->commercial->phone );
        
        
    }


    /** Funciones para reemplazar el texto de los diagnosticos */
    
    protected function replaceDiagnostic($diagnostic){
        $this->_replaceText('{{customer}}', strtoupper($diagnostic->office->crm->name));
        $this->_replaceText('{{customer_city}}', strtoupper($diagnostic->office->city->name));
        $this->_replaceText('{{customer_address}}', $diagnostic->office->address. ', '.$diagnostic->office->city->name);
        if(isset($diagnostic->quote)){
            $this->_replaceSimpleService('{{start-quote-service-simple}}','{{end-quote-service-simple}}', $diagnostic->quote->services);
            $this->_replaceText('{{total-quote-services-value}}', number_format($diagnostic->quote->total,0,'.',',') );

            $days = Carbon::now()->diffInDays(Carbon::parse($diagnostic->quote->expire_date));
            $this->_replaceText('{{quote-expired-difference}}',  $days != 1 ? $days. ' dias': $days. ' dia' );
        }
        $this->_replaceText('{{user-name}}',  Auth::User()->name );
        $this->_replaceText('{{user_headquarter_city}}',  session('headquarter_name') );
        $date = new Date();
        $this->_replaceText('{{date}}',  ucwords($date->format('d F, Y')) );
        $this->_replaceImg('{{user-firm}}',  Auth::User()->signature );
        
    }

    private function _replaceText($term, $value){
        $this->html = str_replace($term,$value, $this->html);
    }

    private function _replaceClass($classname,$newvalue){
        dd($classname,$newvalue);
        $html = mb_convert_encoding($this->html, 'HTML-ENTITIES', "UTF-8");
        $doc = new \DOMDocument("1.0", "utf-8");
        @$doc->loadHTML($html);
        $xpath = new \DOMXPath($doc);
        
        $nodes = $xpath->query("//*[contains(@class, '$classname')]");
        foreach ($nodes as $nodes){
          $nodes->nodeValue=$newvalue;
        }

        $this->html = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());	 
    }

    private function _replaceSimpleService($start,$end,$services){
        
        $_html_array_init  = explode($start, $this->html);
        if(count($_html_array_init )>1){
            $_html_array_end = explode($end,$_html_array_init[1]);
        $_content =$_html_array_end[0];
        $content = "";
        foreach ($services as $index => $service) {
            
            $_service = $_content;
            $_service = str_replace('{{service-name}}',$service->service->name, $_service);
            $_service = str_replace('{{control-name}}', mb_strtolower($service->infestations->pluck('infestation.name')->implode(', ')), $_service);
            $_service = str_replace('{{service-public-observation}}',mb_strtolower($service->public_observation), $_service);
            $content.= $_service;
        }


        $_html = $_html_array_init[0].$content.$_html_array_end[1];
        $this->html = $_html; 
        }
        
    }

    private function _replaceProposalService($start,$end,$services){
        
        $_html_array_init  = explode($start, $this->html);
        if(count($_html_array_init )>1){
            $_html_array_end = explode($end,$_html_array_init[1]);
        $_content =$_html_array_end[0];
        $content = "";
        foreach ($services as $index => $service) {
            
            $_service = $_content;
            $_service = str_replace('{{service-name}}',$service->service->name, $_service);
            $formatter = new NumeroALetras();
            $_service = str_replace('{{total-quote-service-price-text}}',$formatter->toMoney($service->price, 0, 'PESOS'), $_service);
            $_service = str_replace('{{total-quote-service-price-value}}', number_format($service->price,0,',','.'), $_service);
            $_service = str_replace('{{quote-service-controls}}', $this->_replaceServiceControls($service->infestations), $_service);
            $content.= $_service;
        }


        $_html = $_html_array_init[0].$content.$_html_array_end[1];
        $this->html = $_html; 
        }
        
    }

    private function _replaceServiceControls($controls){
        $controlsText = "";
        foreach ($controls as $index => $control){
            $controlsText .= ($index == 0 ? 'Un': (($index+1) != count($controls) ? 'un': 'y un')) ." control de ".mb_strtolower($control->infestation->name) . (($index+1) != count($controls) ? ', ': '.');
            
        }

        return $controlsText;
    }

    private function _replaceIndividualService($start,$end,$services){
        $_html_array_init  = explode($start, $this->html);
            if(count($_html_array_init )>1){
                $_html_array_end = explode($end,$_html_array_init[1]);
            $_content =$_html_array_end[0];
            $content = "";
            foreach ($services as $index => $service) {
                
                $_service = $_content;
                $_service = str_replace('{{quote-service-name}}',$service->service->name. ' ('.$service->infestations->implode('infestation.name',', ').')', $_service);
                $_service = str_replace('{{quote-service-description}}',$service->service->name, $_service);

                $_service = $this->_mechanism("{{start-quote-service-mechanisms}}","{{end-quote-service-mechanisms}}",$_service);

                $content.= $_service;
            }


            $_html = $_html_array_init[0].$content.$_html_array_end[1];
            $this->html = $_html; 
        }
    }



    private function _mechanism($start,$end,$html){
        //obtener mecanismos aleatoriamente
        $products = Product::where('category_id',3)->inRandomOrder()->limit(5)->get();
        //$content = view('quote.sub.frames.mechanisms', compact(['products']))->render();

        $_html_array_init  = explode($start, $html);
           if(count($_html_array_init )>1){
           $_html_array_end = explode($end,$_html_array_init[1]);
           $_content =$_html_array_end[0];
           $content = "";

           foreach ($products as $key => $product) {
               $_mech = $_content;
               $_mech = str_replace('{{quote-service-mechanism-picture}}','<img src=\''. $product->full_avatar .'\' style=\'float:left; height:90px; margin-left:8px; margin-right:8px;\' />', $_mech);
               $_mech = str_replace('{{quote-service-mechanism-name}}',$product->name, $_mech);
               $_mech = str_replace('{{quote-service-mechanism-description}}',$product->description, $_mech);
               $content.= $_mech;
           }
           
           $_html = $_html_array_init[0].$content.$_html_array_end[1];
           return $_html; 
       }
   }


    private function _replaceImg($term, $value){
        $html = '<img src="'.$value.'" style="width:200px;" />';
        $this->html = str_replace($term,$html,$this->html);
    }

    /**
     * Print a log to the query in raw string
     * @param EloquentBuider $builder
     * @return String  
     */
    protected function queryLog($builder){
        
        DB::enableQueryLog();
        $query = str_replace(array('?'), array('\'%s\''), $builder->toSql());
        $query = vsprintf($query, $builder->getBindings());
        return $query;
    }
}
