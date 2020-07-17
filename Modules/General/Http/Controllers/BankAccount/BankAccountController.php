<?php

namespace Modules\General\Http\Controllers\BankAccount;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\BankAccount;

class BankAccountController extends Controller
{
    public function index()
    {
        return view('general::bank_account.index');
    }


    public function store(Request $request)
    {
        $request->validate([
            'bank' => 'required',
            'currency' => 'required',
            'account_data' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
            'kcp_name' => 'required',
            'kcp_address' => 'required',
        ]);

        $bank = new BankAccount();

        $supplier_code = ($request->account_data=='supplier') ? $request->supplier_code : 'smm';

        $save = $bank->create([
            'bank_id' => $request->bank,
            'currency_id' => $request->currency,
            'supplier_code' => $supplier_code,
            'account_data' => $request->account_data,
            'account_type' => 'bank',
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'kcp_name' => $request->kcp_name,
            'kcp_address' => $request->kcp_address,
            'description' => $request->description,
            'is_primary' => $request->is_primary,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'act' => 'New',
            'message' => __('Berhasil menambahkan data akun bank.'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $bank = BankAccount::find($id);

        $request->validate([
            'bank' => 'required',
            'currency' => 'required',
            'account_data' => 'required',
            'account_type' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
            'kcp_name' => 'required',
            'kcp_address' => 'required',
        ]);

        $supplier_code = ($request->account_data=='supplier') ? $request->supplier_code : 'smm';
        $bank->update([
            'bank_id' => $request->bank,
            'currency_id' => $request->currency,
            'supplier_code' => $supplier_code,
            'account_data' => $request->account_data,
            'account_type' => 'bank',
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'kcp_name' => $request->kcp_name,
            'kcp_address' => $request->kcp_address,
            'description' => $request->description,
            'is_primary' => $request->is_primary,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'id' => $id,
            'changed' => changeDetection($bank),
            'act' => 'Update',
            'message' => 'Berhasil memperbaharui data akun bank.',
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data bank.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data bank.';
        }

        if ($bank = BankAccount::find($id)) {
            $bank->update([ 'is_active' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }
}
