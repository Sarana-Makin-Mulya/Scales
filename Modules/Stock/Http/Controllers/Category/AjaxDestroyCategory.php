<?php

namespace Modules\Stock\Http\Controllers\Category;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\ItemCategory;

class AjaxDestroyCategory extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($category = ItemCategory::find($id)) {
            $category->delete();
            return response()->json(['message' => 'Berhasil menghapus data kategori dengan nama "'. $category->name .'".']);
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
