<?php

namespace Modules\General\Http\Controllers\Machine;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Machine;

class AjaxDestroyMachine extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($machine = Machine::find($id)) {
            $machine->delete();
            return response()->json(['message' => 'Berhasil menghapus data mesin dengan nama "'. $machine->name.' - '.$machine->serial_number .'".']);
        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
