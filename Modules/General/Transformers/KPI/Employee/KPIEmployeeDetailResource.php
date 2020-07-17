<?php

namespace Modules\General\Transformers\KPI\Employee;

use Illuminate\Http\Resources\Json\Resource;

class KPIEmployeeDetailResource extends Resource
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
            'kpi_employee_id' => $this->kpi_employee_id,
            'kpi_formula_id' => $this->kpi_formula_id,
            'assessment_point' => $this->kpiFormula->assessment_point,
            'target' => $this->kpiFormula->target,
            'assessment_category' => $this->assessment_category,
            'target_score'=> $this->target_score,
            'percen' => $this->percen,
        ];
    }
}
