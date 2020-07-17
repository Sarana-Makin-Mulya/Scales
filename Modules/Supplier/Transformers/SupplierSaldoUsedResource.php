<?php

namespace Modules\Supplier\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Employee\Http\Controllers\AjaxGetEmployeeDetail;
use Modules\Supplier\Entities\Supplier;
use Modules\Supplier\Entities\SupplierAddress;
use Modules\Supplier\Entities\SupplierCategory;
use Modules\Supplier\Entities\SupplierContact;
use Modules\Supplier\Transformers\SupplierCategory\SupplierCategoryResource;

class SupplierSaldoUsedResource extends Resource
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
            'parent_id' => $this->parent_id,
            'used_id' => $this->used_id,
            'payment_code_parent' => $this->parent->payment_code,
            'payment_code_used' => $this->used->payment_code,
            'nominal' => $this->nominal,
            'issue_date' => $this->issue_date,
            'issued_by' => $this->issued_by,
            'issued_by_name' => getEmployeeFullName($this->issued_by),
        ];
    }
}
