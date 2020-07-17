<?php

namespace Modules\General\Http\Controllers\KPI\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\KpiEmployee;

class AjaxDestroyKPIEmployee extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($KpiEmployee = KpiEmployee::find($id)) {
            DB::beginTransaction();
            try {
                $KpiEmployee->delete();
                $KpiEmployee->KpiEmployeeDetail()->delete();

                DB::commit();
                return response()->json(['message' => 'Berhasil menghapus data KPI Karyawan']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
