<?php

namespace Modules\Supplier\Http\Controllers\SupplierCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Supplier\Entities\SupplierCategory;

class AjaxDestroySupplierCategory extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($supplierCategory = SupplierCategory::find($id)) {
            $supplierCategory->delete();
            return response()->json(['message' => 'Berhasil menghapus data kategori supplier dengan nama "'. $supplierCategory->name .'".']);
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
