<?php

namespace Modules\Supplier\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class SupplierSaldoUsed extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table    = "supplier_saldo_useds";

    protected $fillable = [
        'parent_id',
        'used_id',
        'nominal',
        'issue_date',
        'issued_by',
        'is_active',
    ];

    protected static $logName       = 'Supplier Saldo Used';
    protected static $logFillable   = true;
    protected static $logOnlyDirty  = true;

    // Relations Table
    public function parent()
    {
        return $this->belongsTo(SupplierSaldo::class, 'parent_id', 'id');
    }

    public function used()
    {
        return $this->belongsTo(SupplierSaldo::class, 'used_id', 'id');
    }
}
