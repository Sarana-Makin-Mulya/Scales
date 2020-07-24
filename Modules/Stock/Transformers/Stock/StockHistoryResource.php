<?php

namespace Modules\Stock\Transformers\Stock;

use Illuminate\Http\Resources\Json\Resource;

class StockHistoryResource extends Resource
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
            'item_code' => $this->item_code,
            'item_detail' =>  getItemName($this->item_code),
            'quantity' => $this->quantity,
            'item_unit_conversion' => getUnitConversionName($this->item_unit_conversion_id),
            'transaction_date' => $this->transaction_date,
            'entry_status' => $this->entry_status,
            'transaction_symbol' => $this->transaction_symbol,
            'transaction_name' => $this->transaction_name,
            'transaction_code' => $this->transaction_code,
            'transaction_date' => $this->transaction_date,
            'ic_goods_request_item_id' => $this->ic_goods_request_item_id,
            'ic_goods_request_item_out_id' => $this->ic_goods_request_item_out_id,
            'ic_goods_borrow_item_id' => $this->ic_goods_borrow_item_id,
            'stock_adjustment_item_id' => $this->stock_adjustment_item_id,
            'delivery_order_item_id' => $this->delivery_order_item_id,
            'ic_goods_return_id' => $this->ic_goods_return_id,
            'note' => $this->note,
        ];
    }
}
