<?php

namespace Modules\General\Http\Controllers\Currency;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Currency;
use Modules\General\Transformers\Currency\CurrencyOptionResource;

class AjaxGetCurrencyOptions extends Controller
{
    public function __invoke(Request $request)
    {
            $currency = Currency::query()
            ->orderBy('name', 'ASC')
            ->get();

        return CurrencyOptionResource::collection($currency);
    }
}
