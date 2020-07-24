<?php

namespace Modules\Stock\Http\Controllers\Brand;

use Illuminate\Http\Request;
use Modules\Stock\Entities\Brand;
use Illuminate\Routing\Controller;

class AjaxDestroyBrand extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($brand = Brand::find($id)) {
            $brand->delete();
            return response()->json(['message' => 'Berhasil menghapus data merek barang dengan nama "'. $brand->name .'".']);
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
