<?php

namespace Modules\General\Transformers\KPI;

use Illuminate\Http\Resources\Json\Resource;
use Modules\General\Entities\KpiEmployeeDetail;
use Modules\General\Entities\KpiGroupDetail;

class KpiFormulaResource extends Resource
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
            'assessment_point' => $this->assessment_point,
            'kpi_category' => getKPICategoryName($this->kpi_category_id),
            'unit' => $this->unit,
            'target' => $this->target,
            'formula_a' => $this->formula_a,
            'formula_b' => $this->formula_b,
            'formula_percen' => $this->formula_percen,
            'formula_code' => $this->formula_code,
            'status' => (boolean) $this->is_active,
            'created_at' => $this->created_at,
            'checked' => $this->checkKPIGroupDetail($this->kpi_employee_id, $this->id),
        ];
    }

    public function checkKPIGroupDetail($kpi_employee_id, $id)
    {
        $detail = KpiEmployeeDetail::query()
            ->where('kpi_employee_id', $kpi_employee_id)
            ->where('kpi_formula_id', $id)
            ->first();

        return (!empty($detail)) ? 1: 0;
    }
}
