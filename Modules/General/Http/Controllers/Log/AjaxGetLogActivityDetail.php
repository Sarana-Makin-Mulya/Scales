<?php

namespace Modules\General\Http\Controllers\Log;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Transformers\Log\LogActivityResource;
use Spatie\Activitylog\Models\Activity;

class AjaxGetLogActivityDetail extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {

        $query = Activity::query();
        $data = $query
            ->where('id',13128)
            ->first();

        if (!empty($data)) {
            return New LogActivityResource($data);
        } else {
            return "404";
        }
    }
}
