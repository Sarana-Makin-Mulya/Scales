<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class UserGroup extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'user_groups';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected static $logName = 'User Group';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function user()
    {
        return $this->hasMany(User::class, 'user_group_id', 'id');
    }
}
