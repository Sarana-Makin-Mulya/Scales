<?php

namespace Modules\Stock\Http\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\Item;

class AjaxDestroyItem extends Controller
{
    public function __invoke(Request $request, $code)
    {
        if ($item = Item::find($code)) {

            DB::beginTransaction();
            try {
                $item->delete();
                $item->images()->delete();
                $item->unitConversion()->delete();

                DB::commit();
                return response()->json(['message' => 'Berhasil menghapus data barang dengan nama"'. $item->detail .'".']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }

        }

        return response()->json(['message' => 'Tidak terdapat data dengan kode '. $code], 404);
    }
}
