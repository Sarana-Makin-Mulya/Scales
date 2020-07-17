<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\GeneralMenu;

class UserGroupMenu extends Model
{
    use SoftDeletes;

    protected $table = 'user_group_menus';

    protected $fillable = [
        'menu_id',
        'user_group_id',
        'is_active',
    ];

    public function generalMenu()
    {
        return $this->belongsTo(GeneralMenu::class, 'menu_id', 'id');
    }
}
