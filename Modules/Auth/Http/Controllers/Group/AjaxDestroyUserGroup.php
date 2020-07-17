<?php

namespace Modules\Auth\Http\Controllers\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\UserGroup;

class AjaxDestroyUserGroup extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($group = UserGroup::find($id)) {
            $group->delete();
            return response()->json(['message' => 'Berhasil menghapus data group dengan nama "'. $group->name .'".']);
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
