<?php

namespace Modules\General\Http\Controllers\Socket;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\Notification;
use Modules\General\Entities\NotificationPopup;
use Modules\General\Transformers\Socket\NotificationResource;

class AjaxGetNotificationPopUp extends Controller
{
    public function __invoke(Request $request)
    {
        if (getAuthLevel()=="super-admin") {
            $to = getAuthGroupSlug(session('user_level'));
        } else {
            $to = getAuthLevel();
        }

        $employee_nik = getAuthEmployeeNik(session('user_level'));
        $notification_group_id = getNotificationGroup($to);
        $type   = (empty($request->type)) ? 'notification' : $request->type;


        $notification =DB::table('notifications')
        ->where('type', $type)
            ->where('notification_group_id', $notification_group_id)
            ->whereNotIn('id', function ($query) use ($employee_nik) {
                $query->select('notification_id')
                      ->from('notification_popups')
                      ->where('employee_nik', $employee_nik)
                      ->whereRaw('notification_popups.notification_id = notifications.id');
            })
            ->orderBy('created_at', 'ASC')
            ->take(1)
            ->get();

        foreach ($notification as $notif) {
            $employee_nik = getAuthEmployeeNik(session('user_level'));
            $checkNotif = NotificationPopup::query()
                ->where('notification_id', $notif->id)
                ->where('employee_nik', $employee_nik)
                ->get();

            if ($checkNotif->count()<=0) {
                $save = NotificationPopup::create([
                    'notification_id' => $notif->id,
                    'employee_nik' => $employee_nik,
                    ]);
            }

        }

        return NotificationResource::collection($notification);
    }
}
