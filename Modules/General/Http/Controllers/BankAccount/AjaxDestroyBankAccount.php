<?php

namespace Modules\General\Http\Controllers\BankAccount;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\BankAccount;

class AjaxDestroyBankAccount extends Controller
{
    public function __invoke($id)
    {
        if ($bank = BankAccount::find($id)) {
            $bank->delete();
            return response()->json(['message' => 'Berhasil menghapus data akun bank dengan nama "'. $bank->name .'".']);
        }
    }
}
