<?php

namespace Modules\General\Http\Controllers\Unit;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Unit;

class AjaxDestroyUnit extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($unit = Unit::find($id)) {
            $unit->delete();
            return response()->json(['message' => 'Berhasil menghapus data satuan dengan nama "'. $unit->name .'".']);
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
