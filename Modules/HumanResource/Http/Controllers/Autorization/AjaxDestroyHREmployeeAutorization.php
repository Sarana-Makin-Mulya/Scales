<?php

namespace Modules\HumanResource\Http\Controllers\Autorization;

use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\HREmployeeAuthorization;
use Modules\HumanResource\Entities\HREmployeeAuthorizationDetail;

class AjaxDestroyHREmployeeAutorization extends Controller
{
    public function __invoke($id)
    {
        if ($authorization = HREmployeeAuthorization::find($id)) {
            $authorization->delete();
            HREmployeeAuthorizationDetail::where('employee_authorization_id', $authorization->id)->delete();
            return response()->json(['message' => 'Berhasil menghapus data otorisasi dengan nama "'. $authorization->name .'".']);
        }
    }
}
