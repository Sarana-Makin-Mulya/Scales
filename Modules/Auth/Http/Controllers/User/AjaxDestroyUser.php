<?php

namespace Modules\Auth\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\User;

class AjaxDestroyUser extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($user = User::find($id)) {
            $user->delete();
            return response()->json(['message' => 'Berhasil menghapus data user dengan username "'. $user->username .'".']);
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
