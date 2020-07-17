<?php

namespace Modules\General\Transformers\KPI\Employee;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\KpiEmployeeDetail;

class KPIEmployeeResultResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $KpiEmployeeDetail = KpiEmployeeDetail::where('kpi_employee_id', $this->id)->get();
        $persentase        = KpiEmployeeDetail::query()
                                ->select(DB::raw('sum(percen) as total_percen'))
                                ->where('assessment_category', 'presentase')
                                ->where('kpi_employee_id', $this->id)
                                ->first();
        return [
            'kpi_employee_id' => $this->id,
            'user_group_id' => $this->user_group_id,
            'user_group' => (!empty($this->userGroup)) ? $this->userGroup->name : null,
            'employee_nik' => $this->employee_nik,
            'employee_name' => getEmployeeFullName($this->employee_nik),
            'employee_department' => getHREmployeeDepartmentName($this->employee_nik),
            'total_assesment' => ($KpiEmployeeDetail->count()>0) ? $KpiEmployeeDetail->count() : 0,
            'total_persentase' => $persentase->total_percen."%",
            'filterDate' => $this->filterDate,
            'kpi_date' => getMonthName(substr($this->filterDate,5,2))." ".substr($this->filterDate,0,4),
            'score' => getKPIEmployeeScore($this->id, 'score', $this->filterDate),
            'value' => getKPIEmployeeScore($this->id, 'value', $this->filterDate),
            'assessment_point' => getKPIEmployeeDetail($this->id, $this->filterDate),
            'url_update' => route('general.kpi.employee.update', [$this->id]),
        ];
    }
}
