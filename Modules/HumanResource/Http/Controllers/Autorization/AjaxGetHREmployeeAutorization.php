<?php

namespace Modules\HumanResource\Http\Controllers\Autorization;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\HREmployeeAuthorization;
use Modules\HumanResource\Transformers\Autorization\HREmployeeAutorizationResource;

class AjaxGetHREmployeeAutorization extends Controller
{
    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $keyword  = $request->keyword;
        $currency = HREmployeeAuthorization::query()
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('code', 'LIKE', '%' . $keyword . '%');
            })
        ->orderBy($this->orderBy, $this->sortBy)
        ->paginate($request->per_page);

        return HREmployeeAutorizationResource::collection($currency);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'updated_at';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
