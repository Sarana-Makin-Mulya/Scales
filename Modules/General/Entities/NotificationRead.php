<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationRead extends Model
{
    use SoftDeletes;
    protected $table = 'notification_reads';

    protected $fillable = [
        'notification_id',
        'read_by',
        'read_at',
    ];

    public function notification()
    {
        return $this->belongsTo(notification::class, 'notification_id', 'id');
    }
}
