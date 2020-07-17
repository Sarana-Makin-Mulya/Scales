<?php

namespace Modules\PublicWarehouse\Http\Controllers\WeighingItem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PublicWarehouse\Entities\WeighingItems;

class AjaxDestroyWeighingItem extends Controller
{
    public function __invoke(Request $request, $code)
    {
        if ($weighingItems = WeighingItems::find($code)) {
            $weighingItems->delete();
            return response()->json(['message' => 'Berhasil menghapus data barang penimbangan']);
        }

        return response()->json(['message' => 'Tidak terdapat data dengan code '. $code], 404);
    }
}
