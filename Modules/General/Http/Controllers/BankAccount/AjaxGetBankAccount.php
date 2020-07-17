<?php

namespace Modules\General\Http\Controllers\BankAccount;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\BankAccount;
use Modules\General\Transformers\BankAccount\BankAccountResource;

class AjaxGetBankAccount extends Controller
{
    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $bankAccount = BankAccount::query()
            ->where('account_name', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return BankAccountResource::collection($bankAccount);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'updated_at';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
