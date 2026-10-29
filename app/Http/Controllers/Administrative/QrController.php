<?php

namespace App\Http\Controllers\Administrative;

use App\Qr;
use App\QrLot;
use App\PaperSize;
use App\Headquarter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Exception;

class QrController extends Controller
{

    private $msgError;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('qr/index');
    }

    public function list() {
        $qrs = QrLot::withCount('qrs')
                ->withCount('actives')
                ->headquarter('true')
                ->orderBy('id', 'desc')
                ->get();
        $list = view('qr.sub.list', compact('qrs'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    public function form(Request $request){
        $id             = $request->input('id');
        $qr             = Qr::find($id);
        $paperSize      = PaperSize::enabled()->get();
        $form           = view('qr.forms.qrform', compact(['qr','paperSize']))->render();

        return response(['success' => true, 'view' => $form]);

    }

    public function headquarters(Request $request){
        $query = Headquarter::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function papersizes(Request $request){
        $query = PaperSize::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function store(Request $request)
    {
        $qrLot = new QrLot();
        $success = $this->save($request, $qrLot);
        return response(['success' => $success, 'message' => $success ? 'Qrs Generados correctamente': 'Se presentó un problema guardando los Qrs. ' . $this->msgError]);
    }

    public function save(Request $request, QrLot $qrLot, $action = 'create'){

        $this->validateForm($request);
        try {
            $qrLot->paper_size_id = $request->input('paper_size_id');
            $qrLot->orientation = $request->input('orientation');
            $qrLot->headquarter_id = $request->input('headquarter_id');
            $qrLot->save();

            if($action == 'create'){
                for($i=0; $i<$request->input('quantity'); $i++){
                    $qrLot->qrs()->create([
                        'token' => str_random(10),
                        'use'   => $request->use,
                    ]);
                }
            }
            return true;
        } catch (Exception $ex) {
            $this->msgError = $ex->getMessage();
            return false;
        }
    }

    public function validateForm(Request $request){
        $niceNames = [
            'use'               => 'Uso',
            'headquarter_id'    => 'Sede',
            'quantity'          => 'Cantidad',
            'paper_size_id'     => 'Papel',
            'orientation'       => 'Orientación'
        ];

        $request->validate([
            'use'               => 'required',
            'headquarter_id'    => 'required',
            'quantity'          => 'required|numeric|min:1|max:300',
            'paper_size_id'     => 'required',
            'orientation'       => 'required'
        ],[],$niceNames);
    }

    public function pdf($id)
    {
        $qrLot = QrLot::with(['qrs','paperSize'])
            ->orderBy('id', 'desc')
            ->headquarter()
            ->find($id);
        
        $customSize = [0,0, 269,85];
        $pdf = PDF::loadView('qr.sub.pdf',compact(['qrLot']))->setPaper($customSize, $qrLot->orientation);
         return $pdf->stream();

    }

    function getPlatform($user_agent) {
        $plataformas = array(
           'Windows 10' => 'Windows NT 10.0+',
           'Windows 8.1' => 'Windows NT 6.3+',
           'Windows 8' => 'Windows NT 6.2+',
           'Windows 7' => 'Windows NT 6.1+',
           'Windows Vista' => 'Windows NT 6.0+',
           'Windows XP' => 'Windows NT 5.1+',
           'Windows 2003' => 'Windows NT 5.2+',
           'Windows' => 'Windows otros',
           'iPhone' => 'iPhone',
           'iPad' => 'iPad',
           'Mac OS X' => '(Mac OS X+)|(CFNetwork+)',
           'Mac otros' => 'Macintosh',
           'Android' => 'Android',
           'BlackBerry' => 'BlackBerry',
           'Linux' => 'Linux',
        );
        foreach($plataformas as $plataforma=>$pattern){
           if (preg_match('/(?i)'.$pattern.'/', $user_agent))
              return $plataforma;
        }
        return 'Otras';
     }

    
     public function deleteForm(Request $request){
        $id        = $request->input('id');
        $qrLot      = Qr::find($id);
        $form      = view('qr.forms.deleteForm', compact('qrLot'))->render();

        return response(['success' => true, 'view' => $form]);

    }
    
    public function delete(Request $request){
        try {
            DB::beginTransaction();
            $id     = $request->input('id');
            $qrLot  = QrLot::with('qrs')->find($id);
            Qr::destroy($qrLot->qrs->pluck('id'));
            $success = QrLot::destroy($qrLot->id);
            DB::commit();
            return response(["success" => $success, "message" => $success ? 'Lote eliminado correctamente' : 'No se encontraron Qrs para eliminar']); 
        } catch (Exception $ex) {
            DB::rollBack();
            return response(["success" => false, "message" =>  "Se presentó un error intentando eliminar el lote. ".$ex->getMessage()]); 
        }
        
    }

    public function checkQr(Request $request, $token){
        $qr = Qr::with(['scanBy','crm','office','extend','area'])->where('token', $token)->withTrashed()->get()->last();

        if(!is_null($qr)){
            //Buscar reemplazos
            if( !is_null($qr->file_extend_id)){
                $other_qrs = QR::where('file_extend_id', $qr->extend->id)->whereNotIn('id', [$qr->id])->withTrashed()->get();
            }else{
                $other_qrs = collect([]);
            }
            
        }else{
            abort(406, 'El código de qr que intentas buscar no existe');
        }

        $form           = view('qr.forms.checkqr', compact(['qr','other_qrs']))->render();

        return response(['success' =>true, 'results' => $qr, 'view' => $form]);
    }
}
