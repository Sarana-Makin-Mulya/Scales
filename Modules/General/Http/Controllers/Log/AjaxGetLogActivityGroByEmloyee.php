<?php

namespace Modules\General\Http\Controllers\Log;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Transformers\Log\LogActivityGroupByEmloyeeResource;
use Spatie\Activitylog\Models\Activity;

class AjaxGetLogActivityGroByEmloyee extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $query = Activity::query();
        $data = $query
            ->where('causer_id', '>', 0)
            ->groupBy('causer_id')
            ->get();

        return LogActivityGroupByEmloyeeResource::collection($data);
    }
}
