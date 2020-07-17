<?php

namespace Modules\General\Http\Controllers\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\Notification;
use Modules\General\Transformers\Notification\NotificationDataResource;

class AjaxGetNotification extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        if (getAuthLevel()=="super-admin") {
            $to = getAuthGroupSlug(session('user_level'));
        } else {
            $to = getAuthLevel();
        }

        $read_by = getAuthEmployeeNik(session('user_level'));
        $notification_group_id = getNotificationGroup($to);
        $type   = (empty($request->type)) ? 'notification' : $request->type;


        $notification = Notification::query()
            ->where('type', $type)
            ->where('notification_group_id', $notification_group_id)
            ->where('message', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return NotificationDataResource::collection($notification);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'created_at';
    }
}
