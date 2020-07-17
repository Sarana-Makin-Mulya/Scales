<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\GeneralMenu;

class UserMenu extends Model
{
    use SoftDeletes;

    protected $table = 'user_menus';

    protected $fillable = [
        'menu_number',
        'menu_id',
        'user_group_id',
        'employee_nik',
        'is_active',
    ];

    public function generalMenu()
    {
        return $this->belongsTo(GeneralMenu::class, 'menu_id', 'id');
    }
}
