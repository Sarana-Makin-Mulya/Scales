<?php

namespace Modules\General\Http\Controllers\Bank;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Bank;

class AjaxDestroyBank extends Controller
{
    public function __invoke($id)
    {
        if ($bank = Bank::find($id)) {
            $bank->delete();
            return response()->json(['message' => 'Berhasil menghapus data bank dengan nama "'. $bank->name .'".']);
        }
    }
}
