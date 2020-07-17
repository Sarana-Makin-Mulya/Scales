<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationGroup extends Model
{
    use SoftDeletes;
    protected $table = 'notification_groups';

    protected $fillable = [
        'name',
        'slug',
        'desc',
    ];

    public function notification()
    {
        return $this->hasMany(notification::class, 'notification_group_id', 'id');
    }
}
