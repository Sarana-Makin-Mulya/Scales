<?php

namespace Modules\Auth\Http\Controllers\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\UserGroup;
use Modules\Auth\Transformers\Group\UserGroupOptionsResource;

class AjaxGetUserGroupOptions extends Controller
{

    public function __invoke(Request $request)
    {
        $userGroup = UserGroup::query()
            ->where('is_active',1)
            ->orderBy('name', 'asc')
            ->get();

        return UserGroupOptionsResource::collection($userGroup);
    }
}
