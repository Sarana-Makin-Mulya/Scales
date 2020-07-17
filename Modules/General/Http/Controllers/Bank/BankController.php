<?php

namespace Modules\General\Http\Controllers\Bank;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Bank;

class BankController extends Controller
{

    public function index()
    {
        return view('general::bank.index');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:general_bank',
        ]);

        $bank = new Bank();

        $save = $bank->create([
            'name' => strtoupper($request->name),
            'description' => $request->description,
        ]);

        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'act' => 'New',
            'message' => __('Berhasil menambahkan data bank.'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $bank = Bank::find($id);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $bank->update([
            'name' => strtoupper($request->name),
            'description' => $request->description,
        ]);

        return response()->json([
            'id' => $id,
            'changed' => changeDetection($bank),
            'act' => 'Update',
            'message' => 'Berhasil memperbaharui data bank.',
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data bank.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data bank.';
        }

        if ($bank = Bank::find($id)) {
            $bank->update([ 'is_active' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }

}
