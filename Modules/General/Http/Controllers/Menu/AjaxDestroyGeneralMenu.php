<?php

namespace Modules\General\Http\Controllers\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\GeneralMenu;

class AjaxDestroyGeneralMenu extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($generalMenu = GeneralMenu::find($id)) {
            DB::beginTransaction();
            try {
                GeneralMenu::where('parent_id', $id)->delete();
                $generalMenu->delete();
                DB::commit();
                return response()->json(['message' => 'Berhasil menghapus menu"'. $generalMenu->name .'".']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
