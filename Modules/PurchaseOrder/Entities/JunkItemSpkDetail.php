<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class JunkItemSpkDetail extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "junk_item_spk_details";
    const REQUEST         = 1;
    const DONE            = 2;
    const CANCEL          = 3;


    protected $fillable = [
        'junk_item_spk_code',
        'junk_item_code',
        'junk_item_price_id',
        'price',
        'unit_id',
        'description',
        'is_active',
        'status',
    ];

    protected static $logName = 'SPK BS Detail';
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

   public function JunkItemSPK()
   {
       return $this->belongsTo(JunkItemSpk::class, 'Junk_item_spk_code', 'code');
   }
}
