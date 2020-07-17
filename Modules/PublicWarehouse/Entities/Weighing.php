<?php

namespace Modules\PublicWarehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HumanResource\Entities\HREmployee;
use Modules\PurchaseOrder\Entities\JunkItemRequest;
use Modules\PurchaseOrder\Entities\JunkItemRequestDetail;
use Modules\PurchaseOrder\Entities\PurchaseOrder;
use Modules\PurchaseOrder\Entities\PurchaseOrderItems;
use Spatie\Activitylog\Traits\LogsActivity;

class Weighing extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table      = "weighings";

    // Input Type
    const INPUT_MANUAL      = 1;
    const INPUT_AUTOMATIC   = 2;
    // Stage
    const WEIGHING_FIRST    = 1;
    const WEIGHING_SECOND   = 2;
    // Status
    const PROCESS           = 1;
    const COMPLETED         = 2;
    const CANCEL            = 3;

    protected $fillable = [
        'weighing_category_id',
        'junk_item_request_code',
        'junk_item_request_detail_id',
        'purchase_order_code',
        'purchasing_purchase_order_item_id',
        'weighing_item_code',
        'do_code',
        'first_operator_by',
        'second_operator_by',
        'receiper',
        'driver_name',
        'supplier_code',
        'supplier_name',
        'first_number_plate',
        'second_number_plate',
        'first_datetime',
        'second_datetime',
        'first_weight',
        'second_weight',
        'tolerance_weight',
        'tolerance_reason',
        'file',
        'issue_date',
        'issued_by',
        'input_type',
        'stage',
        'status',
    ];

    protected static $logName = 'Penimbangan Barang';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_code', 'code');
    }

    public function weighingCategory()
    {
        return $this->belongsTo(WeighingCategory::class, 'weighing_category_id', 'id');
    }

    public function junkItemRequest()
    {
        return $this->belongsTo(JunkItemRequest::class, 'junk_item_request_code', 'code');
    }

    public function junkItemRequestDetail()
    {
        return $this->belongsTo(JunkItemRequestDetail::class, 'junk_item_request_detail_id', 'id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_code', 'code');
    }

    public function purchaseOrderItems()
    {
        return $this->belongsTo(PurchaseOrderItems::class, 'purchasing_purchase_order_item_id', 'id');
    }

    public function weighingItems()
    {
        return $this->belongsTo(WeighingItems::class, 'weighing_item_code', 'code');
    }

    public function employeeIsseudBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }
}
