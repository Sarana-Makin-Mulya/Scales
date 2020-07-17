<?php

namespace Modules\General\Http\Controllers\Currency;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Currency;
use Modules\General\Transformers\Currency\CurrencyResource;

class AjaxGetCurrency extends Controller
{
    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $currency = Currency::query()
            ->where('name', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('symbol', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return CurrencyResource::collection($currency);
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
