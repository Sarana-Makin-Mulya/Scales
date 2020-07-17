<?php

namespace Modules\HumanResource\Http\Controllers\Autorization;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Entities\HREmployeeAuthorization;
use Modules\HumanResource\Entities\HREmployeeAuthorizationDetail;

class HREmployeeAutorizationController extends Controller
{
    public function index()
    {
        return view('humanresource::autorization.index');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:hrd_employee_authorizations',
            'code' => 'required|unique:hrd_employee_authorizations',
        ]);

        $data = [
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        DB::beginTransaction();
        try {
            $autorization = new HREmployeeAuthorization();
            $save    = $autorization->create($data);
            $details = $request->details;
            if (!empty($details)) {
                foreach ($details as $detail) {
                    $data = [
                        'employee_authorization_id' => $save->id,
                        'department_id' => $detail['department_id'],
                        'employee_nik' => $detail['employee_nik'],
                        'is_active' =>  1,
                    ];
                   HREmployeeAuthorizationDetail::create($data);
                }
            }

            DB::commit();
            return response()->json([
                'code' => $save->id,
                'changed' => true,
                'act' => 'New',
                'message' => __('Berhasil menambahkan data otorisasi.'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function update(Request $request, $id)
    {
        if ($HREmployeeAuthorization = HREmployeeAuthorization::find($id)) {
            $data = [
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ];

            DB::beginTransaction();
            try {
                $HREmployeeAuthorization->update($data);
                HREmployeeAuthorizationDetail::where('employee_authorization_id', $id)->update(['is_active' => 0]);

                $details = $request->details;
                if (!empty($details)) {
                    foreach ($details as $detail) {
                        $data = [
                            'employee_authorization_id' => $id,
                            'department_id' => $detail['department_id'],
                            'employee_nik' => $detail['employee_nik'],
                            'is_active' =>  1,
                        ];

                        if ($detail['id']>0) {
                            HREmployeeAuthorizationDetail::where('id', $detail['id'])->update($data);
                        } else {
                            HREmployeeAuthorizationDetail::create($data);
                        }
                    }
                }

                DB::commit();
                return response()->json([
                    'code' => $id,
                    'changed' => changeDetection($HREmployeeAuthorization),
                    'act' => 'Update',
                    'message' => __('Berhasil mengubah data otorisasi.'),
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

}
