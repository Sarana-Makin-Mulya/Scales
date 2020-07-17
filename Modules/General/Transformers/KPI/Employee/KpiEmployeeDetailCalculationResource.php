<?php

namespace Modules\General\Transformers\KPI\Employee;

use Illuminate\Http\Resources\Json\Resource;

class KpiEmployeeDetailCalculationResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $nik          = $this->KpiEmployee->employee_nik;
        $formula_code = $this->kpiFormula->formula_code;

        $kpi        = getKPIScore($nik, $formula_code, $this->target_score, $this->filterDate);
        $finalScore = getFinalScore($kpi['score'], $this->percen);
        return [
            'id' => $this->id,
            'kpi_employee_id' => $this->kpi_employee_id,
            'kpi_formula_id' => $this->kpi_formula_id,
            'formula_code' => $formula_code,
            'assessment_point' => $this->kpiFormula->assessment_point,
            'target' => $this->kpiFormula->target,
            'assessment_category' => $this->assessment_category,
            'target_score'=> $this->target_score,
            'percen' => $this->percen,
            'filterDate' => $this->filterDate,
            'a' => $kpi['a'],
            'b' => $kpi['b'],
            'score' => $kpi['score'],
            'percen' => $this->percen,
            'grade' => getKPIGrade($kpi['score']),
            'value' => $finalScore,
        ];
    }
}
