<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockClosing extends Model
{
    use SoftDeletes;
    protected $table    = "stock_closings";

    // Status
    const ACTIVE        = 1;
    const CANCEL        = 0;

    protected $fillable = [
        'issue_date',
        'issued_by',
        'note',
        'status',
    ];

    public function stockClosing()
    {
        return $this->hasMany(StockTransaction::class, 'stock_closing_id', 'id');
    }
}
