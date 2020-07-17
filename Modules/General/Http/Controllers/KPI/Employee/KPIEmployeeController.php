<?php

namespace Modules\General\Http\Controllers\KPI\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\KpiEmployee;
use Modules\General\Entities\KpiEmployeeDetail;

class KPIEmployeeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            // 'user_group_id' => 'required',
            'employee_nik' => 'required',
            'items.*.kpi_formula_id' => 'required',
            'items.*.assessment_category' => 'required',
            'items.*.target_score' => 'required',
            'items.*.percen' => 'required',
        ]);

        $data = [
            'user_group_id' => $request->input('user_group_id'),
            'employee_nik' => $request->input('employee_nik'),
        ];

        DB::beginTransaction();
        try {
            $KpiEmployee = new KpiEmployee();
            $save  = $KpiEmployee->create($data);
            $items = $request->input('items');
            if (!empty($items)) {
                foreach ($items as $item) {
                    $data = [
                        'kpi_employee_id' => $save->id,
                        'kpi_formula_id' => $item['kpi_formula_id'],
                        'assessment_category' => $item['assessment_category'],
                        'target_score' => $item['target_score'],
                        'percen' => $item['percen'],
                        'is_active' => 1,
                    ];
                    KpiEmployeeDetail::create($data);
                }
            }

            DB::commit();
            return response()->json(['message' => __('Berhasil menambahkan kpi karyawan.')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        if ($KpiEmployee = KpiEmployee::find($id)) {
            $request->validate([
                // 'user_group_id' => 'required',
                'employee_nik' => 'required',
                'items.*.kpi_formula_id' => 'required',
                'items.*.assessment_category' => 'required',
                'items.*.target_score' => 'required',
                'items.*.percen' => 'required',
            ]);

            $data = [
                'user_group_id' => $request->input('user_group_id'),
                'employee_nik' => $request->input('employee_nik'),
            ];

            DB::beginTransaction();
            try {
                $KpiEmployee->update($data);
                $items = $request->input('items');
                KpiEmployeeDetail::where('kpi_employee_id', $id)->update(['is_active' => false]);
                if (!empty($items)) {
                    foreach ($items as $item) {
                        $data = [
                            'kpi_employee_id' => $id,
                            'kpi_formula_id' => $item['kpi_formula_id'],
                            'assessment_category' => $item['assessment_category'],
                            'target_score' => $item['target_score'],
                            'percen' => $item['percen'],
                            'is_active' => 1,
                        ];
                        if (empty($item['id'])) {
                            KpiEmployeeDetail::create($data);
                        } else {
                            KpiEmployeeDetail::where('id', $item['id'])->update($data);
                        }
                    }
                }

                KpiEmployeeDetail::where('kpi_employee_id', $id)->where('is_active', false)->delete();

                DB::commit();
                return response()->json(['message' => __('Berhasil mengubah kpi karyawan.')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
