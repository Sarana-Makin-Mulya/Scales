<?php

namespace Modules\General\Transformers\KPI\Employee;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\KpiEmployeeDetail;

class KPIEmployeeResource extends Resource
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
            'id' => $this->id,
            'user_group_id' => $this->user_group_id,
            'user_group' => (!empty($this->userGroup)) ? $this->userGroup->name : null,
            'employee_nik' => $this->employee_nik,
            'employee_name' => getEmployeeFullName($this->employee_nik),
            'employee_department' => getHREmployeeDepartmentName($this->employee_nik),
            'total_assesment' => ($KpiEmployeeDetail->count()>0) ? $KpiEmployeeDetail->count() : 0,
            'total_persentase' => $persentase->total_percen."%",
            'items' => KPIEmployeeDetailResource::collection($this->KpiEmployeeDetail),
            'url_update' => route('general.kpi.employee.update', [$this->id]),
            'url_delete' => route('ajax.general.destroy.kpi.employee', [$this->id]),
        ];
    }
}
