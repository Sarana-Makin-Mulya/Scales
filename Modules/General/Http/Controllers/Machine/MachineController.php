<?php

namespace Modules\General\Http\Controllers\Machine;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Machine;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('general::machine.index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:general_machines',
            'serial_number' => 'required',
            'capacity' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'serial_number' => $request->serial_number,
            'capacity' => $request->capacity,
            'description' => $request->description,
        ];

        $machine = new Machine();
        $save = $machine->create($data);

        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'act' => 'New',
            'message' => 'Berhasil menambah data mesin.',
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if ($machine = Machine::find($id)) {
            $request->validate([
                'name' => 'required|unique:general_machines,id,'.$id,
                'serial_number' => 'required',
                'capacity' => 'required',
            ]);

            $data = [
                'name' => $request->name,
                'serial_number' => $request->serial_number,
                'capacity' => $request->capacity,
                'description' => $request->description,
            ];

            $machine->update($data);

            return response()->json([
                'id' => $id,
                'changed' => changeDetection($machine),
                'act' => 'Update',
                'message' => __('Berhasil memperbaharui data mesin.'),
            ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data mesin.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data mesin.';
        }

        if ($machine = Machine::find($id)) {
            $machine->update([ 'is_active' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }
}
