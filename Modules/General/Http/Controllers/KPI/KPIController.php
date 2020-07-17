<?php

namespace Modules\General\Http\Controllers\KPI;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\KpiGroup;
use Modules\General\Entities\KpiGroupDetail;

class KPIController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('general::kpi.kpi_main');
    }

    public function store(Request $request)
    {
        $request->validate([
            //'user_group_id' => 'required|unique:general_currencies',
            'user_group_id' => 'required',
            'items_formula.*.kpi_formula_id' => 'required',
            'items_formula.*.assessment_point' => 'required',
            // 'items_formula.*.target' => 'required',
            'items_formula.*.percen' => 'required',
        ]);

        $data = [
            'user_group_id' => $request->input('user_group_id'),
        ];

        DB::beginTransaction();
        try {
            $KpiGroup = new KpiGroup();
            $save  = $KpiGroup->create($data);
            $items_formula = $request->items_formula;
            if (!empty($items_formula)) {
                foreach ($items_formula as $item) {
                    $data = [
                        'kpi_group_id' => $save->id,
                        'kpi_formula_id' => $item['kpi_formula_id'],
                        'percen' => $item['percen'],
                    ];
                    KpiGroupDetail::create($data);
                }
            }

            DB::commit();
            return response()->json(['message' => __('Berhasil menambahkan kpi group.')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        if ($KpiGroup = KpiGroup::find($id)) {
            $request->validate([
                //'user_group_id' => 'required|unique:general_currencies',
                'user_group_id' => 'required',
                'items_formula.*.kpi_formula_id' => 'required',
                'items_formula.*.assessment_point' => 'required',
                // 'items_formula.*.target' => 'required',
                // 'items_formula.*.percen' => 'required',
            ]);

            $data = [
                'user_group_id' => $request->input('user_group_id'),
            ];

            DB::beginTransaction();
            try {
                $KpiGroup->update($data);
                $items_formula = $request->items_formula;
                KpiGroupDetail::where('kpi_group_id', $id)->update(['is_active' => false]);
                if (!empty($items_formula)) {
                    foreach ($items_formula as $item) {
                        $data = [
                            'kpi_group_id' => $id,
                            'kpi_formula_id' => $item['kpi_formula_id'],
                            'percen' => $item['percen'],
                            'is_active' => true,
                        ];
                        if (empty($item['id'])) {
                            KpiGroupDetail::create($data);
                        } else {
                            KpiGroupDetail::where('id', $item['id'])->update($data);
                        }
                    }
                }

                KpiGroupDetail::where('kpi_group_id', $id)->where('is_active', false)->delete();

                DB::commit();
                return response()->json(['message' => __('Berhasil mengubah kpi group.')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Tidak terdapat data dengan nomor '. $id], 404);
    }
}
