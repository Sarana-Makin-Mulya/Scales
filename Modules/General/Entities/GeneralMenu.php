<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class GeneralMenu extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'general_menus';

    protected $fillable = [
        'menu_number',
        'parent_id',
        'name',
        'url',
        'route',
        'icon',
        'level',
        'is_active',
    ];

    protected static $logName = 'Master Menu';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
}
