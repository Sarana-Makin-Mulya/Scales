<?php

namespace Modules\General\Http\Controllers\BankAccount;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\BankAccount;
use Modules\General\Transformers\BankAccount\BankAccountOptionsResource;

class AjaxGetBankAccountOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $query = BankAccount::query();

        if ($request->ad=="supplier") {
            $query = $query
                ->where('account_data', 'supplier')
                ->where('supplier_code', $request->code);
        } else {
            $query = $query->where('account_data', 'company')
                ->where('supplier_code', 'smm');
        }

        $bank = $query
            ->where('is_active', 1)
            ->orderBy('account_name', 'ASC')
            ->get();

        return BankAccountOptionsResource::collection($bank);
    }
}
