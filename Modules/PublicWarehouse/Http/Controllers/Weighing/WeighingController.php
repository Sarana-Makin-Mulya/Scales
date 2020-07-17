<?php

namespace Modules\PublicWarehouse\Http\Controllers\Weighing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\PublicWarehouse\Entities\Weighing;

class WeighingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('publicwarehouse::weighing.index');
    }


    public function store(Request $request)
    {

        $request->validate([
            'weighing_category_id' => 'required',
            'do_code' => 'required',
            'first_operator_by' => 'required',
            'second_operator_by' => 'required',
            'receiper' => 'required',
            'driver_name' => 'required',
            'supplier_name' => 'required',
            'first_number_plate' => 'required',
            'second_number_plate' => 'required',
            'first_datetime' => 'required',
            'second_datetime' => 'required',
            'first_weight' => 'required',
            'second_weight' => 'required',
        ]);

        $data = [
            'weighing_category_id' => $request->weighing_category_id,
            'do_code' => $request->do_code,
            'first_operator_by' => $request->first_operator_by,
            'second_operator_by' => $request->second_operator_by,
            'receiper' => $request->receiper,
            'driver_name' => $request->driver_name,
            'supplier_code' => $request->supplier_code,
            'supplier_name' => $request->supplier_name,
            'first_number_plate' => $request->first_number_plate,
            'second_number_plate' => $request->second_number_plate,
            'first_datetime' => $request->first_datetime,
            'second_datetime' => $request->second_datetime,
            'first_weight' => $request->first_weight,
            'second_weight' => $request->second_weight,
            'tolerance_weight' => $request->tolerance_weight,
            'tolerance_reason' => $request->tolerance_reason,
            'issued_by' => Auth::user()->employee_nik,
            'issue_date' => date('Y-m-d H:i:s'),
        ];

        $weighing = new Weighing();
        $save = $weighing->create($data);

        /* B : UPLOAD FILE */
        $file = $request->file('file');
        $data_file      = null;
        if (!empty($file)) {
            $id        = $save->id;
            $path      = 'uploads/weighing';
            $name      = 'weighing-'.str_pad($id,4,0,STR_PAD_LEFT).'.'. $file->getClientOriginalExtension();
            $data_file = $file->storeAs($path, $name);
            $data = [
                'file' => $path.'/'.$name,
            ];

            $weighing->where('id', $id)->update($data);
        }
        /* E : UPLOAD FILE */


        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'foto' =>$data_file,
            'act' => 'New',
            'message' => 'Berhasil menambah data penimbangan.',
        ]);
    }


    public function update(Request $request, $id)
    {
        if ($weighing = Weighing::find($id)) {
            $file_prev  = $weighing->file;
            $request->validate([
                'weighing_category_id' => 'required',
                'do_code' => 'required',
                'first_operator_by' => 'required',
                'second_operator_by' => 'required',
                'receiper' => 'required',
                'driver_name' => 'required',
                'supplier_name' => 'required',
                'first_number_plate' => 'required',
                'second_number_plate' => 'required',
                'first_datetime' => 'required',
                'second_datetime' => 'required',
                'first_weight' => 'required',
                'second_weight' => 'required',
            ]);

            $data = [
                'weighing_category_id' => $request->weighing_category_id,
                'do_code' => $request->do_code,
                'first_operator_by' => $request->first_operator_by,
                'second_operator_by' => $request->second_operator_by,
                'receiper' => $request->receiper,
                'driver_name' => $request->driver_name,
                'supplier_code' => $request->supplier_code,
                'supplier_name' => $request->supplier_name,
                'first_number_plate' => $request->first_number_plate,
                'second_number_plate' => $request->second_number_plate,
                'first_datetime' => $request->first_datetime,
                'second_datetime' => $request->second_datetime,
                'first_weight' => $request->first_weight,
                'second_weight' => $request->second_weight,
                'tolerance_weight' => $request->tolerance_weight,
                'tolerance_reason' => $request->tolerance_reason,
                'issued_by' => Auth::user()->employee_nik,
                'issue_date' => date('Y-m-d H:i:s'),
            ];

            $weighing->update($data);

            /* B : UPLOAD FILE */
            $file = $request->file('file');
            $data_file      = null;
            if (!empty($file)) {
                /* B : REMOVE FILE */
                if (!empty($file_prev)) {
                    Storage::delete($file_prev);
                }
                /* E : REMOVE FILE */
                $path      = 'uploads/weighing';
                $name      = 'weighing-'.str_pad($id,4,0,STR_PAD_LEFT).'.'. $file->getClientOriginalExtension();
                $data_file = $file->storeAs($path, $name);
                $data = [
                    'file' => $path.'/'.$name,
                ];
                $weighing->where('id', $id)->update($data);
            }
            /* E : UPLOAD FILE */

            return response()->json([
                'id' => $id,
                'changed' => changeDetection($weighing),
                'act' => 'Update',
                'foto' =>$data_file,
                'message' => __('Berhasil memperbaharui data penimbangan.'),
            ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function Download(Request $request)
    {
        return Storage::download('uploads/weighing/'.$request->file_name);
    }
}
