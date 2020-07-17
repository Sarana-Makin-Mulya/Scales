<?php

namespace Modules\Supplier\Http\Controllers\Supplier;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Supplier\Entities\Supplier;

class AjaxDestroySupplier extends Controller
{
    public function __invoke(Request $request, $code)
    {
        if ($supplier = Supplier::find($code)) {
            DB::beginTransaction();
            try {
                $supplier->delete();
                $supplier->contacts()->delete();
                $supplier->addresses()->delete();

                DB::commit();
                return response()->json(['message' => 'Berhasil menghapus data supplier dengan nomor "'. $supplier->name .'".']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $code], 404);
    }
}
