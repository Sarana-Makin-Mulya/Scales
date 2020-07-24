<?php

namespace Modules\Stock\Transformers\StockClosing;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Stock\Entities\StockClosing;
use Modules\Stock\Entities\StockTransaction;

class StockClosingResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'issue_date' => $this->issue_date,
            'closing_month_year' => $this->issue_date,
            'note' => $this->note,
            'issued_by' => $this->issued_by,
            'total_transaction' => $this->getTotalTransaction($this->id),
            'data_status' => $this->getLastClosingStock($this->id),
            'issued_by_name' => getEmployeeFullName($this->issued_by),
            'url_edit' => route('stock.closing.update',$this->id),
            'url_cancel' => route('stock.closing.cancel',$this->id),
        ];
    }

    public function getTotalTransaction($id)
    {
        $StockTransaction = StockTransaction::query()
            ->where('entry_status', '<>', StockTransaction::STOCK_SUMMARY)
            ->where('stock_closing_id', $id)
            ->get();

        return $StockTransaction->count();
    }

    public function getLastClosingStock($id)
    {
        $ClosingStock = StockClosing::query()
            ->where('status', StockClosing::ACTIVE)
            ->orderBy('issue_date', 'Desc')
            ->first();
        if (!empty($ClosingStock)) {
            return ($ClosingStock->id==$id) ? 0 : 1;
        } else {
            return 0;
        }
    }
}
