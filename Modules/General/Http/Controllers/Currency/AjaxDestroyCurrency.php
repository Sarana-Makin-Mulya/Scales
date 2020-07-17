<?php

namespace Modules\General\Http\Controllers\Currency;

use Illuminate\Routing\Controller;
use Modules\General\Entities\Currency;

class AjaxDestroyCurrency extends Controller
{
    public function __invoke($id)
    {
        if ($currecy = Currency::find($id)) {
            $currecy->delete();
            return response()->json(['message' => 'Berhasil menghapus data mata uang dengan nama "'. $currecy->name .'".']);
        }
    }
}
