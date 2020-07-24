<?php

namespace Modules\Stock\Transformers\StockOpname;

use Illuminate\Http\Resources\Json\Resource;
use Symfony\Component\Process\Process;

class StockOpnameDailyResource extends Resource
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
            'file_name' => $this->file_name,
            'total_item' => $this->total_item,
            'issue_date' => $this->issue_date,
            'issued_by' => $this->issued_by,
            'issued_by_name' => getEmployeeFullName($this->issued_by),
            'status' => $this->status,
            'url_edit' => route('stock.opname.daily.update', [$this->id]),
            'url_delete' => route('ajax.stock.destroy.stock.opname.daily', [$this->id]),
        ];
    }
}

// status => process, completed, canceled, deleted
