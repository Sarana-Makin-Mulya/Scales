<?php

namespace Modules\Stock\Transformers\StockQuarantine;

use DateTime;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Transformers\FindItemResource;

class CheckStockQuarantineResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->filterStatus=="return_to_stock") {
            $stockTransaction = StockTransaction::where('id', $this->stock_transaction_id)->first();
            $fdate     = substr($stockTransaction->transaction_date, 0, 10);
            $tdate     = substr($this->action_date, 0, 10);
            $datetime1 = new DateTime($fdate);
            $datetime2 = new DateTime($tdate);
            $interval  = $datetime1->diff($datetime2);
            $days      = $interval->format('%a');//now do whatever you like with $days
            $stock_out = $stockTransaction->stock_out;
            $stock_current = $stockTransaction->stock_current;
            $transaction_name = $stockTransaction->transaction_name;
            $transaction_code = $stockTransaction->transaction_code;
            $transaction_date = $stockTransaction->transaction_date;
            $stock_quarantine_id = $stockTransaction->stock_quarantine_id;
            $stock_quarantine_date = $stockTransaction->stock_quarantine_date;
            $reason = $this->reason;
            $action_date = $this->action_date;
            $issued_by = getEmployeeFullName($this->issued_by);
        } else {
            $fdate     = substr($this->transaction_date, 0, 10);
            $tdate     = date('Y-m-d');
            $datetime1 = new DateTime($fdate);
            $datetime2 = new DateTime($tdate);
            $interval  = $datetime1->diff($datetime2);
            $days      = $interval->format('%a');//now do whatever you like with $days
            $stock_out = $this->stock_out;
            $stock_current = $this->stock_current;
            $transaction_name = $this->transaction_name;
            $transaction_code = $this->transaction_code;
            $transaction_date = $this->transaction_date;
            $stock_quarantine_id = $this->stock_quarantine_id;
            $stock_quarantine_date = $this->stock_quarantine_date;
            $reason = null;
            $action_date = null;
            $issued_by = null;
        }
        return [
            'id' => $this->id,
            'filterStatus' => $this->filterStatus,
            'item' => New FindItemResource($this->item),
            'quantity' => $this->quantity,
            'stock_out' => $stock_out,
            'stock_current' => $stock_current,
            'item_unit_conversion_id' => $this->item_unit_conversion_id,
            'item_unit_conversion' => getUnitConversionName($this->item_unit_conversion_id),
            'transaction_name' => $transaction_name,
            'transaction_code' => $transaction_code,
            'transaction_date' => $transaction_date,
            'days' => $days,
            'action_date' => $action_date,
            'datetime1' => $datetime1,
            'datetime2' => $datetime2,
            'issued_by' => $issued_by,
            'po_code' => $this->po_code,
            'stock_quarantine_id' => $stock_quarantine_id,
            'stock_quarantine_date' => $stock_quarantine_date,
            'note' => $this->note,
            'reason' => $reason,
        ];
    }
}
