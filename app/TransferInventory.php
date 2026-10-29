<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class TransferInventory extends Model
{

    use SoftDeletes, GeneralScope;

    protected $guarded = [];

    public function headquarter ()
    {
        return $this->belongsTo (Headquarter::class,'headquarter_id');
    }

    public function warehouse ()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id')->withTrashed();
    }

    public function detailTranferInventory ()
    {
       return $this->hasMany(TransferInventoryDetail::class);
    }

    public function warehouseTransfer ()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_transfer')->withTrashed();
    }

    public function orderPurchases ()
    {
        return $this->belongsTo(OrderPurchase::class,'order_id');
    }
    public function documentType()
    {
        return $this->belongsTo(ConfigurarionHeadquarterFoxcon::class,'document_type_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function foxconDocument(){
        return $this->belongsTo(FoxconDocument::class,'document_foxcon_id');
    }

    public function eppFoxconDocument(){
        return $this->belongsTo(FoxconDocument::class,'epp_document_foxcon_id');
    }


}
