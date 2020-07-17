<?php

namespace Modules\General\Http\Controllers\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Notification;
use Modules\General\Entities\NotificationRead;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $filter_type = (empty($request->type)) ? 'notification' : $request->type;
        return view('general::notification.index', compact('filter_type'));
    }

    public function updateNotif(Request $request)
    {
        $read_by = getAuthEmployeeNik(session('user_level'));
        $notif = NotificationRead::query()
            ->where('notification_id', $request->id)
            ->where('read_by', $read_by)
            ->get();

        if ($notif->count()<=0) {
            $save = NotificationRead::create([
                'notification_id' => $request->id,
                'read_by' => $read_by,
                'read_at' => date('Y-m-d H:i:s')
                ]);
            return "success". $save;
        }
    }
}
