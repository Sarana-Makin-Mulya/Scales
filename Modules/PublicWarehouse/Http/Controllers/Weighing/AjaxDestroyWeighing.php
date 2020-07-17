<?php

namespace Modules\PublicWarehouse\Http\Controllers\Weighing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\PublicWarehouse\Entities\Weighing;

class AjaxDestroyWeighing extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($weighing = Weighing::find($id)) {
            $file  = $weighing->file;
            $weighing->delete();

            if (!empty($file)) {
                /* B : REMOVE FILE */
                Storage::delete($file);
                /* E : REMOVE FILE */
            }
            return response()->json(['message' => 'Berhasil menghapus data penimbangan']);
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
