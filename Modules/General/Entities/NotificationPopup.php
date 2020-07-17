<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationPopup extends Model
{
    use SoftDeletes;
    protected $table = 'notification_popups';

    protected $fillable = [
        'notification_id',
        'employee_nik',
    ];

    public function notification()
    {
        return $this->belongsTo(notification::class, 'notification_id', 'id');
    }
}
