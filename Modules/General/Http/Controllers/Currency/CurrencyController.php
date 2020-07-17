<?php

namespace Modules\General\Http\Controllers\Currency;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Currency;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('general::currency.index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:general_currencies',
            'symbol' => 'required|unique:general_currencies',
        ]);

        $currency = new Currency();

        $save = $currency->create([
            'name' => strtoupper($request->name),
            'symbol' => $request->symbol,
        ]);

        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'act' => 'New',
            'message' => __('Berhasil menambahkan data mata uang.'),
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
        $currency = Currency::find($id);

        $request->validate([
            'name' => 'required',
            'symbol' => 'required',
        ]);

        $currency->update([
            'name' => strtoupper($request->name),
            'symbol' => $request->symbol,
        ]);

        return response()->json([
            'id' => $id,
            'changed' => changeDetection($currency),
            'act' => 'Update',
            'message' => 'Berhasil memperbaharui data mata uang.',
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data mata uang.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data mata uang.';
        }

        if ($currency = Currency::find($id)) {
            $currency->update([ 'is_active' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }

    public function getCurrency(Request $request)
    {
        $currency = Currency::where('id', $request->id)->first();
        if (!empty($currency)) {
            return $currency;
        }
        return ['id' => null, 'name' => null, 'symbol' => null ];
    }
}
