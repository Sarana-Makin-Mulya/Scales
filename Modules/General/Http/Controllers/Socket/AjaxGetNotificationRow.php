<?php

namespace Modules\General\Http\Controllers\Socket;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\Notification;

class AjaxGetNotificationRow extends Controller
{
    public function __invoke(Request $request)
    {
        if (getAuthLevel()=="super-admin") {
            $to = getAuthGroupSlug(session('user_level'));
        } else {
            $to = getAuthLevel();
        }

        $read_by = getAuthEmployeeNik(session('user_level'));
        $notification_group_id = getNotificationGroup($to);
        $type   = (empty($request->type)) ? 'notification' : $request->type;


        $notification =DB::table('notifications')
        ->where('type', $type)
            ->where('notification_group_id', $notification_group_id)
            ->whereNotIn('id', function ($query) use ($read_by) {
                $query->select('notification_id')
                      ->from('notification_reads')
                      ->where('read_by', $read_by)
                      ->whereRaw('notification_reads.notification_id = notifications.id');
            })
            ->orderBy('created_at', 'Desc')
            ->get();

        return ['notification' => $notification->count()];
    }
}
