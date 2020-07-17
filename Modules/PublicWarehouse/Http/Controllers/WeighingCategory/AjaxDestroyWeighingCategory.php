<?php

namespace Modules\PublicWarehouse\Http\Controllers\WeighingCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\PublicWarehouse\Entities\Weighing;
use Modules\PublicWarehouse\Entities\WeighingCategory;

class AjaxDestroyWeighingCategory extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($weighingCategory = WeighingCategory::find($id)) {
            $weighingCategory->delete();
            return response()->json(['message' => 'Berhasil menghapus data kategori penimbangan']);
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
