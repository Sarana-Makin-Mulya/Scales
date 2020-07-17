<?php

namespace Modules\General\Http\Controllers\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Unit;
use Modules\General\Http\Requests\UnitRequest;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('general::unit.index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(UnitRequest $request)
    {
        $unit = new Unit();
        $save = $unit->create([
            'symbol' => $request->symbol,
            'name' => strtoupper($request->name),
        ]);

        return response()->json([
            'id' => $save->id,
            'act' => 'New',
            'changed' => true,
            'message' => __('Berhasil menambahkan data satuan baru.'),
            ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UnitRequest $request, $id)
    {
        $unit = Unit::find($id);

        $unit->update([
            'symbol' => $request->symbol,
            'name' => strtoupper($request->name),
        ]);

        return response()->json([
            'id' => $id,
            'act' => 'Update',
            'changed' => changeDetection($unit),
            'message' => 'Berhasil memperbaharui data satuan.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data satuan.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data satuan.';
        }

        if ($unit = Unit::find($id)) {
            $unit->update([ 'is_active' => $request->status ]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }
}
