<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\Unit;
use Spatie\Activitylog\Traits\LogsActivity;

class JunkItemRequestDetail extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "junk_item_request_details";
    const REQUEST         = 1;
    const DONE            = 2;
    const CANCEL          = 3;


    protected $fillable = [
        'junk_item_request_code',
        'junk_item_code',
        'junk_item_price_id',
        'price',
        'quantity',
        'unit_id',
        'description',
        'weighing_date',
        'weighing_by',
        'weighing_desc',
        'report_date',
        'report_by',
        'is_active',
        'status',
    ];

    protected static $logName = 'Request Penjualan Barang Sampah';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

   // Relations Table
   public function junkItemPrice()
   {
       return $this->belongsTo(JunkItemPrice::class, 'junk_item_price_id', 'id');
   }

   public function items()
   {
       return $this->belongsTo(JunkItem::class, 'junk_item_code', 'code');
   }

   public function unit()
   {
       return $this->belongsTo(Unit::class, 'item_unit_conversion_id', 'id');
   }

   public function JunkItemRequest()
   {
       return $this->belongsTo(JunkItemRequest::class, 'junk_item_request_code', 'code');
   }

}
