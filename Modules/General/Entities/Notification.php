<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;
    protected $table = 'notifications';

    const STATUS_NEW  = 0;
    const STATUS_READ = 1;
    const STATUS_DONE = 2;

    protected $fillable = [
        'notification_group_id',
        'type',
        'transaction_type',
        'transaction_code',
        'transaction_id',
        'from',
        'to',
        'message',
        'url',
        'status',
        'issued_by',
        'read_at',
    ];

    public function notificationRead()
    {
        return $this->hasMany(notificationRead::class, 'notification_id', 'id');
    }

    public function notificationPopup()
    {
        return $this->hasMany(notificationPopup::class, 'notification_id', 'id');
    }

    public function notificationGroup()
    {
        return $this->belongsTo(notificationGroup::class, 'notification_group_id', 'id');
    }
}
