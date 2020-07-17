<?php

namespace Modules\General\Http\Controllers\Bank;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Bank;
use Modules\General\Transformers\Bank\BankOptionsResource;

class AjaxGetBankOptions extends Controller
{
    public function __invoke(Request $request)
    {
            $bank = Bank::query()
                ->where('is_active', 1)
                ->orderBy('name', 'ASC')
                ->get();

        return BankOptionsResource::collection($bank);
    }
}
